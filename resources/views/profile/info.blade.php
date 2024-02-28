<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$user->surname}} {{$user->given_name}}
        </h2>
    </x-slot>

    @if(session('message'))
        <div class="alert alert-danger red-text">
            {{ session('message') }}
        </div>
    @endif

    <div class="mx-auto px-6">
        
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
            <div>
            <img src="{{ asset('storage/img/' .  $user->image_file_name) }}">
            </div>
            <div>
            <h1 class="p-4 text-lg font-semibold">
                {{$user->surname}} {{$user->given_name}}
            </h1>
            </div>
            メールアドレス：{{$user->email}}<br>
            生年月日：{{$user->birth_day}}<br>
            電話番号：{{$user->phone}}
                
            <hr class="w-full">
            </div>
        
    </div>
</x-app-layout>
