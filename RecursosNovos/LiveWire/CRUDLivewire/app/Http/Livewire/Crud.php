<?php
namespace App\Http\Livewire;

use Livewire\Component;

class Crud extends Component
{

    public $item;
    public $list = ['Ribamar', 'Fátima', 'Tiago', 'Elias'];
    public $key;
    public $action = "Cadastrar";

    public function render()
    {
        return view('livewire.crud', [ 'name' => 'Ribamar FS']);
    }

    public function add(){
        // Adicionar ao final da lista
        //array_push($this->list, $this->item);
        // Adicionar ao início da lista
        array_unshift($this->list, $this->item);
    }

    public function clear(){
        unset($this->list);
        $this->list = [];
    }

    public function delete(int $key){
        unset($this->list[$key]);
    }

    public function edit(int $key){
        $this->action = "Atualizar";
        $this->key = $key;
        $this->item = $this->list[$key];
    }

    public function update(){
        $this->list = array_replace($this->list, [
            $this->key => $this->item
        ]);
        $this->item = '';
        $this->action = "Cadastrar";
    }

}
