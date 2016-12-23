<?php
namespace Ackintosh\Ganesha;

use Ackintosh\Ganesha;
use Ackintosh\Ganesha\Storage\AdapterInterface;

class Builder
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * Builder constructor.
     *
     * @param Configuration $configuration
     */
    private function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Builder
     */
    public static function create()
    {
        return new self(new Configuration());
    }

    public function withFailureThreshold($threshold)
    {
        $this->configuration->setFailureThreshold($threshold);
        return $this;
    }

    /**
     * @param AdapterInterface $adapter
     * @return $this Builder
     */
    public function withAdapter(AdapterInterface $adapter)
    {
        $this->configuration->setAdapter($adapter);
        return $this;
    }

    /**
     * @param  callable $function
     * @return Builder  $this
     */
    public function withAdapterSetupFunction(callable $function)
    {
        $this->configuration->setAdapterSetupFunction($function);
        return $this;
    }

    /**
     * @return Ganesha
     * @throws \Exception
     */
    public function build()
    {
        try {
            $this->configuration->validate();
        } catch (\Exception $e) {
            throw $e;
        }

        $ganesha = new Ganesha($this->configuration->getFailureThreshold());
        $ganesha->setupStorage($this->configuration->getAdapterSetupFunction());

        return $ganesha;
    }
}
