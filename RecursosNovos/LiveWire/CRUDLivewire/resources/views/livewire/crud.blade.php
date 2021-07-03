<div>
    <input type="text" wire:model="item"><!-- Passar a propriedade $item por data bind para o input -->

    @if($action == "Cadastrar")
        <button wire:click="add()">Cadastrar</button>
    @else
        <button wire:click="update()">Atualizar</button>
    @endif
    <p>
        <button wire:click="clear()">Limpar</button>
    </p>

    <ul>
        @foreach($list as $key=>$name)
        <li>
            <span>{{ $name }}</span>

            <button wire:click="edit({{$key}})">edit</button>
            <button wire:click="delete({{$key}})">x</button>
        </li>
        @endforeach
    </ul>
</div>
