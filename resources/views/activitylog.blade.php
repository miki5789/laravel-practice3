<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            更新履歴
        </h2>

    </x-slot>

    @if(session('message'))
        <div class="alert alert-danger red-text">
            {{ session('message') }}
        </div>
    @endif
    <body>
    
    <div style="width: 100%;">
            
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>更新日時</th>
                    <th>AdminユーザーID</th>
                    <th>対象ユーザーID</th>
                    <th>モデル名</th>
                    <th>モデルID</th>
                    <th>操作タイプ</th>
                    <th>変更前のデータ</th>
                    <th>変更後のデータ</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->updated_at }}</td>
                    <td>{{ $log->operator_id }}</td>
                    <td>{{ $log->target_id }}</td>
                    <td>{{ $log->model }}</td>
                    <td>{{ $log->model_id }}</td>
                    <td>{{ $log->operation_type }}</td>
                    <td>{{ $log->original_data }}</td>
                    <td>{{ $log->new_data }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

    </body>
    {!! $logs->render() !!}
</x-app-layout>
