@extends('app')

@section('content')

    @if( count($vk_groups) )
        @foreach($vk_groups as $vk_group)
            <article>
            <h1>Группа: {{$vk_group->name}}</h1>
            <p>Шапка:</p>
            <img src="/covers/{{$vk_group->cover_image}}" width="400" height="200">
            <p>
                <a href="https://vk.com/im?sel={{ $vk_group->owner_vk_uid }}" target="_blank">
                    Написать владельцу
                </a>
            </p>
            <p><b>Баланс: {{ $vk_group->balance }} руб.</b></p>
            <p>Статус: {{ $vk_group->enabled ? "Включен" : "Остановлен" }}</p>
            </article>
        @endforeach
    @endif
@stop

@section('footer')
    <script>
        $('div.alert').not('.alert-important').delay(3000).slideUp(300);
    </script>
@stop
