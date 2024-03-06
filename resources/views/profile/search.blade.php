<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            検索結果

            <!-- フィルターボタン -->
            <button id="filterButton">フィルター</button>

            <!-- モーダルウィンドウ -->
            <div id="filterModal" class="modal">
                <form method="post" action="{{route('profile.search')}}" class="flex-2">
                    @csrf
                    <div class="modal-content">
                        <span class="close">&times;</span>

                        <label for="filterInput">姓:</label><br>
                        <input type="text" name="filter_surname"><br>

                        <label for="filterInput">名:</label><br>
                        <input type="text" name="filter_given_name"><br>

                
                        <!-- birth day -->
                        <div class="mt-4">      
                            <select id="filter_year" name="filter_year" class="form-select">
                                <option value="">-</option>
                                @foreach(MyFunction::yearSelect() as $year)
                                    <option value="{{ $year }}" {{ old('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                                <x-input-error :messages="$errors->get('filter_year')" class="mt-2" />
                            </select>
                            <label for="filter_year">年</label>

                            <select id="filter_month" name="filter_month" class="form-select">
                                <option value="">-</option>
                                @foreach(MyFunction::monthSelect() as $month)
                                    <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                                <x-input-error :messages="$errors->get('filter_month')" class="mt-2" />
                            </select>
                            <label for="filter_month">月</label>

                            <select id="filter_day" name="filter_day" class="form-select">
                                <option value="">-</option>
                                @foreach(MyFunction::daySelect() as $day)
                                    <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                @endforeach
                                <x-input-error :messages="$errors->get('filter_day')" class="mt-2" />
                            </select>
                            <label for="filter_day">日</label>
                        </div>

                        <label for="filterInput">電話番号:</label><br>
                        <input type="text" name="filter_phone"><br>

                        <x-primary-button class="ms-4">
                            {{ __('検索') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <style>
                /* モーダル */
            .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            }

            /* モーダルの内容 */
            .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            }

            /* 閉じるボタン */
            .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            }

            .close:hover,
            .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
            }

            </style>
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

    {!! $users->render() !!}

    <script>
    // フィルターボタン要素を取得
    var filterButton = document.getElementById("filterButton");

    // モーダル要素を取得
    var modal = document.getElementById("filterModal");

    // モーダルの閉じるボタン要素を取得
    var closeBtn = document.getElementsByClassName("close")[0];

    // フィルターボタンがクリックされたときにモーダルを表示
    filterButton.onclick = function() {
    modal.style.display = "block";
    }

    // モーダルの閉じるボタンがクリックされたときにモーダルを閉じる
    closeBtn.onclick = function() {
    modal.style.display = "none";
    }

    // モーダルの外側がクリックされたときにモーダルを閉じる
    window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    }
    </script>
</x-app-layout>
