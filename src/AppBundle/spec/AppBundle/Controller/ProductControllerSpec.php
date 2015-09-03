<?php

namespace spec\AppBundle\Controller;

use AppBundle\Controller\ProductController;
use AppBundle\Entity\Product;
use AppBundle\Entity\ProductRepository;
use AppBundle\Form\ProductType;
use AppBundle\Request\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Util\Codes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @mixin ProductController
 */
class ProductControllerSpec extends ObjectBehavior
{
    /**
     * @var ProductRepository
     */
    private $repository;

    /**
     * @var EntityMangerInterface
     */
    private $manager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    const VALIDATION_SUCCESSFUL = true;
    const VALIDATION_FAILD = false;

    function let(ProductRepository $repository, EntityManagerInterface $manager, FormFactoryInterface $formFactory)
    {
        $this->repository = $repository;
        $this->manager = $manager;
        $this->formFactory = $formFactory;

        $this->beConstructedWith($repository, $manager, $formFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AppBundle\Controller\ProductController');
    }

    function it_successfully_creates_a_product(Request $request, FormInterface $form, Product $product)
    {
        $this->formFactory->createNamed('', Argument::type(ProductType::class), null)->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isValid()->willReturn(self::VALIDATION_SUCCESSFUL);
        $form->getData()->willReturn($product);

        $this->manager->persist($product)->shouldBeCalled();
        $this->manager->flush()->shouldBeCalled();

        $this->createAction($request)->shouldBeRestViewWith([
            'data' => $product,
            'statusCode' => Codes::HTTP_CREATED,
            'serializationGroups' => ['product_create'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_return_form_on_ineffective_product_creation(Request $request, FormFactoryInterface $formFactory, FormInterface $form, Product $product)
    {
        $this->formFactory->createNamed('', Argument::type(ProductType::class), null)->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isValid()->willReturn(self::VALIDATION_FAILD);
        $form->getData()->shouldNotBeCalled();

        $this->createAction($request)->shouldReturn($form);
    }

    function it_successfully_shows_the_product(Product $product)
    {
        $id = 1;
        $this->repository->find($id)->willReturn($product);

        $this->viewAction($id)->shouldBeRestViewWith([
            'data' => $product,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_view'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_throw_not_found_exception_when_no_product_to_show()
    {
        $id = 2;
        $this->repository->find($id)->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException('Product 2 does not exists'))->duringViewAction($id);
    }

    function it_successfully_updates_a_product(Product $product, FormInterface $form, Request $request)
    {
        $id = 1;
        $this->repository->find($id)->willReturn($product);

        $this->formFactory->createNamed('', Argument::type(ProductType::class), $product)->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isValid()->willReturn(self::VALIDATION_SUCCESSFUL);
        $form->getData()->willReturn($product);

        $this->manager->persist($product)->shouldBeCalled();
        $this->manager->flush()->shouldBeCalled();

        $this->editAction($request, $id)->shouldBeRestViewWith([
            'data' => $product,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_update'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_throw_not_found_exception_when_no_product_to_update(Request $request)
    {
        $id = 2;
        $this->repository->find($id)->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException('Product 2 does not exists'))->duringEditAction($request, $id);
    }

    function it_return_form_on_ineffective_product_update(Product $product, FormInterface $form, Request $request)
    {
        $id = 1;
        $this->repository->find($id)->willReturn($product);

        $this->formFactory->createNamed('', Argument::type(ProductType::class), $product)->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();
        $form->isValid()->willReturn(self::VALIDATION_FAILD);

        $this->editAction($request, $id)->shouldReturn($form);
    }

    function it_successfully_delete_product(Product $product, EntityManagerInterface $manager)
    {
        $id = 1;
        $this->repository->find($id)->willReturn($product);

        $product->inactivate()->shouldBeCalled();

        $manager->persist($product)->shouldBeCalled();
        $manager->flush()->shouldBeCalled();

        $this->removeAction($id);
    }

    function it_throw_not_found_exception_when_no_product_to_remove(Product $product)
    {
        $id = 2;
        $this->repository->find($id)->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException('Product 2 does not exists'))->duringRemoveAction($id);
    }

    function it_successfully_list_products(Product $product)
    {
        $criteria = new Criteria([]);

        $this->repository->countByCriteria($criteria)->willReturn(1);
        $this->repository->findByCriteria($criteria)->willReturn([$product]);

        $this->indexAction($criteria)->shouldBeRestViewWith([
            'data' => ['total' => 1, 'result' => [$product]],
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_index'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }
}
