<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule;

use Aura\Sql\ConnectionLocatorInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\Write;

class AuraSqlConnectionInterceptor implements MethodInterceptor
{

    /**
     * @var ConnectionLocatorInterface
     */
    private $connectionLocator;

    /**
     * @var array
     */
    private $readsMethods = [];

    /**
     * @var array
     */
    private $writeMethods = [];

    /**
     * @param ConnectionLocatorInterface $connectionLocator
     * @param array                      $readMethods
     * @param array                      $writeMethods
     *
     * @Read("readMethods")
     * @Write("writeMethods")
     */
    public function __construct(ConnectionLocatorInterface $connectionLocator, array $readMethods, array $writeMethods)
    {
        $this->connectionLocator = $connectionLocator;
        $this->readsMethods = $readMethods;
        $this->writeMethods = $writeMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $connection =  $this->getConnection($invocation);
        $invocation->getThis()->pdo = $connection;
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @return \Aura\Sql\ExtendedPdoInterface
     */
    private function getConnection(MethodInvocation $invocation)
    {
        $methodName = $invocation->getMethod()->name;
        if (in_array($methodName ,$this->readsMethods)) {
            return $this->connectionLocator->getRead();
        }
        if (in_array($methodName ,$this->writeMethods)) {
            return $this->connectionLocator->getWrite();
        }

        return $this->connectionLocator->getDefault();
    }
}
