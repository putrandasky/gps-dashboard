@extends('layouts.admin')
@section('content')
<div id="app">
  <dashboard/>
</div>
<script src="{{ mix('/js/dashboard/app.js') }}"></script>
@endsection
