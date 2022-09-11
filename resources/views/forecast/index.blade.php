<h1>{{ __('weatherpackage::forecast.index.h1') }}</h1>

<form name="forecast" id="forecast" method="post" action="{{route('forecast.store')}}">
    {{ csrf_field() }}
    <label for="ip">IP</label>
    <input type="text" id="ip" name="ip" value="{{ $ip }}">
    <input type="hidden" id="datetime" name="datetime" value="{{ $datetime }}">
    <br><br>
    <input type="submit" value="{{ __('weatherpackage::forecast.index.submit') }}">
</form>