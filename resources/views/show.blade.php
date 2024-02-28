<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            社員一覧
        </h2>
    </x-slot>

    @if(session('message'))
        <div class="alert alert-danger red-text">
            {{ session('message') }}
        </div>
    @endif

    <div class="mx-auto px-6">
        @foreach($users as $user)
            <div class="mt-4 text-lg font-semibold">
                <div class ="text-right flex">
                    <a href="{{route('profile.edit', ['id'=>$user->id]) }}" class="flex-1">
                        <x-primary-button>
                            編集
                        </x-primary-button>
                    </a>
                    <form method="post" action="{{route('profile.destroy', $user)}}" class="flex-2">
                        @csrf
                        @method('delete')

                        <x-primary-button class="bg-red-700 ml-2">
                            削除
                        </x-primary-button>
                    </form>
                </div>
                <h1 class="p-4 text-lg font-semibold">
                    <a href="{{route('profile.info', ['id'=>$user->id])}} class="text=blue-600">
                        {{$user->surname}} {{$user->given_name}}
                    </a>
                </h1>
                生年月日：{{$user->birth_day}}<br>
                電話番号：{{$user->phone}}
            <hr class="w-full">
            </div>
        @endforeach
    </div>
</x-app-layout>
