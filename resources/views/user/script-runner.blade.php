@extends('user.layouts.master')
@section('title', 'Script Runner')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Live Script Runner</h5>
    </div>
    <div class="card-body">
        <form id="codeForm">
            @csrf
            <textarea name="code" id="codeInput" rows="12" class="form-control mb-3" placeholder="Write HTML, CSS, JS or PHP code..."></textarea>
            <div class="text-end">
                <button type="submit" class="btn btn-success">Run Script</button>
            </div>
        </form>

        <div class="mt-4">
            <h6>Output:</h6>
            <iframe id="outputFrame" class="w-100 border rounded" style="height: 400px;"></iframe>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#codeForm').on('submit', function (e) {
        e.preventDefault();

        $.post("{{ route('user.runScript') }}", {
            _token: '{{ csrf_token() }}',
            code: $('#codeInput').val()
        }, function (res) {
			const iframe = document.getElementById('outputFrame');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write(res.output || 'No output');
            iframeDoc.close();
        });
    });
</script>
@endsection
