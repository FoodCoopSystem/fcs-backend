<?php

namespace spec\FoodCoopBundle\Controller;

use FoodCoopBundle\Actions\ProductCreateAction;
use FoodCoopBundle\Actions\ProductIndexAction;
use FoodCoopBundle\Actions\ProductRemoveAction;
use FoodCoopBundle\Actions\ProductUpdateAction;
use FoodCoopBundle\Controller\ProductController;
use FoodCoopBundle\Entity\Product;
use FoodCoopBundle\Request\Criteria;
use FOS\RestBundle\Util\Codes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin ProductController
 */
class ProductControllerSpec extends ObjectBehavior
{
    function let(ProductCreateAction $create, ProductUpdateAction $update, ProductIndexAction $index, ProductRemoveAction $remove)
    {
        $this->beConstructedWith($create, $update, $index, $remove);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FoodCoopBundle\Controller\ProductController');
    }

    function it_perform_create_action(ProductCreateAction $create)
    {
        $data = [];
        $create->execute()->willReturn($data);

        $this->createAction()->shouldBeRestViewWith([
            'data' => $data,
            'statusCode' => Codes::HTTP_CREATED,
            'serializationGroups' => ['product_create'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_perform_edit_action(ProductUpdateAction $update, Product $product)
    {
        $data = [];
        $update->setProduct($product)->willReturn($update);
        $update->execute()->willReturn($data);

        $this->editAction($product)->shouldBeRestViewWith([
            'data' => $data,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_update'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_perform_index_action(ProductIndexAction $index, Criteria $criteria)
    {
        $data = [];
        $index->setCriteria($criteria)->willReturn($index);
        $index->execute()->willReturn($data);

        $this->indexAction($criteria)->shouldBeRestViewWith([
            'data' => $data,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_index'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }

    function it_perform_remove_action(ProductRemoveAction $remove, Product $product)
    {
        $data = [];
        $remove->setProduct($product)->willReturn($remove);
        $remove->execute()->shouldBeCalled();

        $this->removeAction($product);
    }

    function it_perform_view_action(Product $product)
    {
        $this->viewAction($product)->shouldBeRestViewWith([
            'data' => $product,
            'statusCode' => Codes::HTTP_OK,
            'serializationGroups' => ['product_view'],
            'headers' => [
                'cache-control' => ['no-cache'],
                'date' => ["@string@.isDateTime()"],
            ]
        ]);
    }
}
