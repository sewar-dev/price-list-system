<?php

namespace App\Repositories;

use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Repository
{
    use ValidatesRequests;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     *
     */
    public function __construct()
    {

        $this->model = $this->getModel();
    }

    /**
     *
     */
    abstract function getModel();

    /**
     *
     */
    public function getPrimary()
    {
        return $this->getModel()->getKeyName();
    }

    /**
     *
     */
    public function getById($id)
    {
        return $this->getModel()->find($id);
    }

    /**
     * @param   string $arrayName
     * @param   \Illuminate\Database\Eloquent\Model $model
     *
     * @return  \Illuminate\Support\Collection
     */
    protected function getOld($arrayName, $model)
    {
        $inputs     = collect();
        $inputArray = request()->old($arrayName);
        $keyName    = $this->getPrimary();

        if (is_array($inputArray)) {
            foreach ($inputArray as $index => $data) {
                $input = new $model;
                $input->fill($data);
                $input->{$keyName} = intval($data[$keyName] ?? 0);
                $inputs->add($input);
            }
        }

        return $inputs;
    }

    /**
     * Trigger static method calls to the model
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        return call_user_func_array([new static(), $method], $arguments);
    }

    /**
     * Trigger method calls to the model
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->model, $method], $arguments);
    }
}
