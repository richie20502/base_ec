<div class="address-item @if ($address->is_default) is-default @endif" data-id="{{ $address->id }}">
    <p class="name">{{ $address->name }}</p>
    

    <input class="form-control" id="address_name_default" name="address[name]" autocomplete="family-name" type="hidden"
        value="{{ $address->name }}" required>
    <input class="form-control" id="address_email_default" name="address[email]" autocomplete="email" type="hidden"
        value="{{ $address->email }}" required>
    <input class="form-control" id="address_phone_default" name="address[phone]" autocomplete="phone" type="hidden"
        value="{{ $address->phone }}">
    <input class="form-control" id="address_state_default" name="address[state]" autocomplete="state" type="hidden"
        value="{{ $address->state_name }}" required>
    <input class="form-control" id="address_city_default" name="address[city]" autocomplete="city" type="hidden"
        value="{{ $address->city_name }}" required>
    <input class="form-control" id="address_address_default" name="address[address]" autocomplete="address" type="hidden"
        value="{{ $address->address }}" required>
    <input class="form-control" id="address_zip_code_default" name="address[zip_code]" autocomplete="postal-code" type="hidden"
        value="{{ $address->zip_code }}" required>


    <p class="address"
        title="{{ $address->address }}, {{ $address->city_name }}, {{ $address->state_name }}@if (EcommerceHelper::isUsingInMultipleCountries()) , {{ $address->country_name }} @endif @if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code) , {{ $address->zip_code }} @endif">
        {{ $address->address }}, {{ $address->city_name }}, {{ $address->state_name }}@if (EcommerceHelper::isUsingInMultipleCountries())
            , {{ $address->country_name }}
            @endif @if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code)
                , {{ $address->zip_code }}
            @endif
    </p>
    <p class="phone">{{ __('Phone') }}: {{ $address->phone }}</p>
    @if ($address->email)
        <p class="email">{{ __('Email') }}: {{ $address->email }}</p>
    @endif
    @if ($address->is_default)
        <span class="default">{{ __('Default') }}</span>
    @endif
</div>
