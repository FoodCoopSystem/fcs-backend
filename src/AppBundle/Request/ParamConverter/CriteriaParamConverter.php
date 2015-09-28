<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Request\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CriteriaParamConverter implements ParamConverterInterface
{
    /**
     * Stores the object in the request.
     *
     * @param Request $request The request
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool    True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $queryCriteria = new Criteria(
            $request->get('filter'),
            $request->get('count'),
            $request->get('page'),
            $request->get('sorting')
        );

        $request->attributes->set($configuration->getName(), $queryCriteria);
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration Should be an instance of ParamConverter
     *
     * @return bool    True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Criteria::class;
    }
}
