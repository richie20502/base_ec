
@if($rawTotal > env('AMOUNT_FREE_TRACKING'))

<div class="customer-address-payment-form">
    <input type="hidden" name="update-tax-url" id="update-checkout-tax-url"
        value="{{ route('public.ajax.checkout.update-tax') }}">
    <div class="mb-3 form-group">
        @if (auth('customer')->check())
            <p>{{ __('Account') }}: <strong>{{ auth('customer')->user()->name }}</strong> -
                {!! Html::email(auth('customer')->user()->email) !!} (<a href="{{ route('customer.logout') }}">{{ __('Logout') }})</a>
            </p>
        @else
            <p>{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}">{{ __('Login') }}</a></p>
        @endif
    </div>

    {!! apply_filters('ecommerce_checkout_address_form_before') !!}

    @auth('customer')
        <div class="mb-3 form-group">
            @if ($isAvailableAddress)
                <label class="mb-2 form-label" for="address_id">{{ __('Select available addresses') }}:</label>
            @endif
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="list-customer-address @if (!$isAvailableAddress) d-none @endif">



                <div class="select--arrow">
                    <select class="form-control" id="address_id" name="address[address_id]" @required($isAvailableAddress)>
                        <option value="new" @selected($oldSessionAddressId == 'new')>{{ __('Add new address...') }}</option>
                        @if ($isAvailableAddress)
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected($oldSessionAddressId == $address->id)>
                                    {{ $address->full_address }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                </div>
                <br>
                <div class="address-item-selected @if (!$sessionAddressId) d-none @endif">
                    @if ($isAvailableAddress && $oldSessionAddressId != 'new')
                        @if ($oldSessionAddressId && $addresses->contains('id', $oldSessionAddressId))
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $addresses->firstWhere('id', $oldSessionAddressId),
                            ])
                        @elseif ($defaultAddress = get_default_customer_address())
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $defaultAddress,
                            ])
                        @else
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => Arr::first($addresses),
                            ])
                        @endif
                    @endif
                </div>
                <div class="list-available-address d-none">
                    @if ($isAvailableAddress)
                        @foreach ($addresses as $address)
                            <div class="address-item-wrapper" data-id="{{ $address->id }}">
                                @include(
                                    'plugins/ecommerce::orders.partials.address-item',
                                    compact('address'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endauth

    <div class="address-form-wrapper @if (auth('customer')->check() && $oldSessionAddressId !== 'new' && $isAvailableAddress) d-none @endif">
        <div class="form-group mb-3 @error('address.name') has-error @enderror">
            <div class="form-input-wrapper">
                <input class="form-control" id="address_name" name="address[name]" autocomplete="family-name"
                    type="text"
                    value="{{ old('address.name', Arr::get($sessionCheckoutData, 'name')) ?: (auth('customer')->check() ? auth('customer')->user()->name : null) }}"
                    required>
                <label for="address_name">{{ __('Full Name') }}</label>
            </div>
            {!! Form::error('address.name', $errors) !!}
        </div>

        <div class="row">
            @if (!in_array('email', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-8' => !in_array(
                        'phone',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.email') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input class="form-control" id="address_email" name="address[email]" autocomplete="email"
                                type="email"
                                value="{{ old('address.email', Arr::get($sessionCheckoutData, 'email')) ?: (auth('customer')->check() ? auth('customer')->user()->email : null) }}"
                                required>
                            <label for="address_email">{{ __('Email') }}</label>
                        </div>
                        {!! Form::error('address.email', $errors) !!}
                    </div>
                </div>
            @endif
            @if (!in_array('phone', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-4' => !in_array(
                        'email',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.phone') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input class="form-control" id="address_phone" name="address[phone]" autocomplete="phone"
                                type="tel"
                                value="{{ old('address.phone', Arr::get($sessionCheckoutData, 'phone')) ?: (auth('customer')->check() ? auth('customer')->user()->phone : null) }}">
                            <label for="address_phone">{{ __('Phone') }}</label>
                        </div>
                        {!! Form::error('address.phone', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_checkout_address_form_inside', null) !!}

        @if (EcommerceHelper::isUsingInMultipleCountries() && !in_array('country', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.country') has-error @enderror">
                <div class="select--arrow form-input-wrapper">
                    <select class="form-control" id="address_country" name="address[country]" autocomplete="country"
                        data-form-parent=".customer-address-payment-form" data-type="country" required>
                        @foreach (EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option value="{{ $countryCode }}" @selected(old('address.country', Arr::get($sessionCheckoutData, 'country', EcommerceHelper::getDefaultCountryId())) == $countryCode)>
                                {{ $countryName }}
                            </option>
                        @endforeach
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                    <label for="address_country">{{ __('Country') }}</label>
                </div>
                {!! Form::error('address.country', $errors) !!}
            </div>
        @else
            <input id="address_country" name="address[country]" type="hidden"
                value="{{ EcommerceHelper::getFirstCountryId() }}">
        @endif

        <div class="row">
            @if (!in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div class="col-sm-6 col-12">
                    <div class="form-group mb-3 @error('address.state') has-error @enderror">
                        @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                            <div class="select--arrow form-input-wrapper">
                                <select class="form-control" id="address_state" name="address[state]"
                                    autocomplete="state" data-form-parent=".customer-address-payment-form"
                                    data-type="state" data-url="{{ route('ajax.states-by-country') }}" required>
                                    <option value="">{{ __('Select state...') }}</option>
                                    @if (old('address.country', Arr::get($sessionCheckoutData, 'country') ?: EcommerceHelper::getDefaultCountryId()) ||
                                            !EcommerceHelper::isUsingInMultipleCountries())
                                        @foreach (EcommerceHelper::getAvailableStatesByCountry(old('address.country', Arr::get($sessionCheckoutData, 'country') ?: EcommerceHelper::getDefaultCountryId())) as $stateId => $stateName)
                                            <option value="{{ $stateId }}"
                                                @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) == $stateId) selected @endif>{{ $stateName }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @else
                            <div class="form-input-wrapper">
                                <input class="form-control" id="address_state" name="address[state]"
                                    autocomplete="state" type="text"
                                    value="{{ old('address.state', Arr::get($sessionCheckoutData, 'state')) }}"
                                    required>
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.state', $errors) !!}
                    </div>
                </div>
            @endif

            @if (!in_array('city', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-sm-6 col-12' => !in_array(
                        'state',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                    'col-12' => in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.city') has-error @enderror">
                        @if (EcommerceHelper::useCityFieldAsTextField())
                            <div class="form-input-wrapper">
                                <input class="form-control" id="address_city" name="address[city]" autocomplete="city"
                                    type="text"
                                    value="{{ old('address.city', Arr::get($sessionCheckoutData, 'city')) }}"
                                    required>
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @else
                            <div class="select--arrow form-input-wrapper">
                                <select class="form-control" id="address_city" name="address[city]"
                                    autocomplete="city" data-type="city" data-using-select2="false"
                                    data-url="{{ route('ajax.cities-by-state') }}" required>
                                    <option value="">{{ __('Select city...') }}</option>
                                    @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) ||
                                            in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                                        @foreach (EcommerceHelper::getAvailableCitiesByState(old('address.state', Arr::get($sessionCheckoutData, 'state')), old('address.country', Arr::get($sessionCheckoutData, 'country'))) as $cityId => $cityName)
                                            <option value="{{ $cityId }}"
                                                @if (old('address.city', Arr::get($sessionCheckoutData, 'city')) == $cityId) selected @endif>{{ $cityName }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.city', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_checkout_address_form_after_city_field', null, $sessionCheckoutData) !!}

        @if (!in_array('address', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.address') has-error @enderror">
                <div class="form-input-wrapper">
                    <input class="form-control" id="address_address" name="address[address]" autocomplete="address"
                        type="text"
                        value="{{ old('address.address', Arr::get($sessionCheckoutData, 'address')) }}" required>
                    <label for="address_address">{{ __('Address') }}</label>
                </div>
                {!! Form::error('address.address', $errors) !!}
            </div>
        @endif

        @if (EcommerceHelper::isZipCodeEnabled())
            <div class="form-group mb-3 @error('address.zip_code') has-error @enderror">
                <div class="form-input-wrapper">
                    <input class="form-control" id="address_zip_code" name="address[zip_code]"
                        autocomplete="postal-code" type="text"
                        value="{{ old('address.zip_code', Arr::get($sessionCheckoutData, 'zip_code')) }}" required>
                    <label for="address_zip_code">{{ __('Zip code') }}</label>
                </div>
                {!! Form::error('address.zip_code', $errors) !!}
            </div>
        @endif
    </div>





    @if (!auth('customer')->check())
        <div id="register-an-account-wrapper">
            <div class="mb-3 form-group">
                <input id="create_account" name="create_account" type="checkbox" value="1"
                    @if (old('create_account') == 1) checked @endif>
                <label class="form-label"
                    for="create_account">{{ __('Register an account with above information?') }}</label>
            </div>

            <div class="password-group @if (!$errors->has('password') && !$errors->has('password_confirmation')) d-none @endif">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group  @error('password') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input class="form-control" id="password" name="password" type="password"
                                    autocomplete="new-password">
                                <label for="password">{{ __('Password') }}</label>
                            </div>
                            {!! Form::error('password', $errors) !!}
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group @error('password_confirmation') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input class="form-control" id="password-confirm" name="password_confirmation"
                                    type="password" autocomplete="password-confirmation">
                                <label for="password-confirm">{{ __('Password confirmation') }}</label>
                            </div>
                            {!! Form::error('password_confirmation', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! apply_filters('ecommerce_checkout_address_form_after', null, $sessionCheckoutData) !!}
</div>






@else
<div class="customer-address-payment-form">
    <input type="hidden" name="update-tax-url" id="update-checkout-tax-url"
        value="{{ route('public.ajax.checkout.update-tax') }}">
    <input type="hidden" name="address_default_validate" id="address_default_validate" value="1" readonly>
    <div class="mb-3 form-group">
        @if (auth('customer')->check())
            <p>{{ __('Account') }}: <strong>{{ auth('customer')->user()->name }}</strong> -
                {!! Html::email(auth('customer')->user()->email) !!} (<a href="{{ route('customer.logout') }}">{{ __('Logout') }})</a>
            </p>
        @else
            <p>{{ __('Already have an account?') }} <a href="{{ route('customer.login') }}">{{ __('Login') }}</a></p>
        @endif
    </div>

    {!! apply_filters('ecommerce_checkout_address_form_before') !!}

    @auth('customer')
        <div class="mb-3 form-group">
            @if ($isAvailableAddress)
                <label class="mb-2 form-label" for="address_id">{{ __('Select available addresses') }}:</label>
            @endif
            @php
                $oldSessionAddressId = old('address.address_id', $sessionAddressId);
            @endphp
            <div class="list-customer-address @if (!$isAvailableAddress) d-none @endif">



                <div class="select--arrow">
                    <select class="form-control" id="address_id" name="address[address_id]" @required($isAvailableAddress)>
                        <option value="new" @selected($oldSessionAddressId == 'new')>{{ __('Add new address...') }}</option>
                        @if ($isAvailableAddress)
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}" @selected($oldSessionAddressId == $address->id)>
                                    {{ $address->full_address }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                </div>
                <br>
                <div class="address-item-selected @if (!$sessionAddressId) d-none @endif">
                    @if ($isAvailableAddress && $oldSessionAddressId != 'new')
                        @if ($oldSessionAddressId && $addresses->contains('id', $oldSessionAddressId))
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $addresses->firstWhere('id', $oldSessionAddressId),
                            ])
                        @elseif ($defaultAddress = get_default_customer_address())
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => $defaultAddress,
                            ])
                        @else
                            @include('plugins/ecommerce::orders.partials.address-item', [
                                'address' => Arr::first($addresses),
                            ])
                        @endif
                    @endif
                </div>
                <div class="list-available-address d-none">
                    @if ($isAvailableAddress)
                        @foreach ($addresses as $address)
                            <div class="address-item-wrapper" data-id="{{ $address->id }}">
                                @include(
                                    'plugins/ecommerce::orders.partials.address-item',
                                    compact('address'))
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endauth

    <div class="address-form-wrapper @if (auth('customer')->check() && $oldSessionAddressId !== 'new' && $isAvailableAddress) d-none @endif">
        <div class="form-group mb-3 @error('address.name') has-error @enderror">
            <div class="form-input-wrapper">
                <input class="form-control" id="address_name" name="address[name]" autocomplete="family-name"
                    type="text"
                    value="{{ old('address.name', Arr::get($sessionCheckoutData, 'name')) ?: (auth('customer')->check() ? auth('customer')->user()->name : null) }}"
                    required>
                <label for="address_name">{{ __('Full Name') }}</label>
            </div>
            {!! Form::error('address.name', $errors) !!}
        </div>

        <div class="row">
            @if (!in_array('email', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-8' => !in_array(
                        'phone',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.email') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input class="form-control" id="address_email" name="address[email]" autocomplete="email"
                                type="email"
                                value="{{ old('address.email', Arr::get($sessionCheckoutData, 'email')) ?: (auth('customer')->check() ? auth('customer')->user()->email : null) }}"
                                required>
                            <label for="address_email">{{ __('Email') }}</label>
                        </div>
                        {!! Form::error('address.email', $errors) !!}
                    </div>
                </div>
            @endif
            @if (!in_array('phone', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-12',
                    'col-lg-4' => !in_array(
                        'email',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.phone') has-error @enderror">
                        <div class="form-input-wrapper">
                            <input class="form-control" id="address_phone" name="address[phone]" autocomplete="phone"
                                type="tel"
                                value="{{ old('address.phone', Arr::get($sessionCheckoutData, 'phone')) ?: (auth('customer')->check() ? auth('customer')->user()->phone : null) }}">
                            <label for="address_phone">{{ __('Phone') }}</label>
                        </div>
                        {!! Form::error('address.phone', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_checkout_address_form_inside', null) !!}

        @if (EcommerceHelper::isUsingInMultipleCountries() && !in_array('country', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.country') has-error @enderror">
                <div class="select--arrow form-input-wrapper">
                    <select class="form-control" id="address_country" name="address[country]" autocomplete="country"
                        data-form-parent=".customer-address-payment-form" data-type="country" required>
                        @foreach (EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                            <option value="{{ $countryCode }}" @selected(old('address.country', Arr::get($sessionCheckoutData, 'country', EcommerceHelper::getDefaultCountryId())) == $countryCode)>
                                {{ $countryName }}
                            </option>
                        @endforeach
                    </select>
                    <x-core::icon name="ti ti-chevron-down" />
                    <label for="address_country">{{ __('Country') }}</label>
                </div>
                {!! Form::error('address.country', $errors) !!}
            </div>
        @else
            <input id="address_country" name="address[country]" type="hidden"
                value="{{ EcommerceHelper::getFirstCountryId() }}">
        @endif

        <div class="row">
            @if (!in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div class="col-sm-6 col-12">
                    <div class="form-group mb-3 @error('address.state') has-error @enderror">
                        @if (EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                            <div class="select--arrow form-input-wrapper">
                                <select class="form-control" id="address_state" name="address[state]"
                                    autocomplete="state" data-form-parent=".customer-address-payment-form"
                                    data-type="state" data-url="{{ route('ajax.states-by-country') }}" required>
                                    <option value="">{{ __('Select state...') }}</option>
                                    @if (old('address.country', Arr::get($sessionCheckoutData, 'country') ?: EcommerceHelper::getDefaultCountryId()) ||
                                            !EcommerceHelper::isUsingInMultipleCountries())
                                        @foreach (EcommerceHelper::getAvailableStatesByCountry(old('address.country', Arr::get($sessionCheckoutData, 'country') ?: EcommerceHelper::getDefaultCountryId())) as $stateId => $stateName)
                                            <option value="{{ $stateId }}"
                                                @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) == $stateId) selected @endif>{{ $stateName }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @else
                            <div class="form-input-wrapper">
                                <input class="form-control" id="address_state" name="address[state]"
                                    autocomplete="state" type="text"
                                    value="{{ old('address.state', Arr::get($sessionCheckoutData, 'state')) }}"
                                    required>
                                <label for="address_state">{{ __('State') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.state', $errors) !!}
                    </div>
                </div>
            @endif

            @if (!in_array('city', EcommerceHelper::getHiddenFieldsAtCheckout()))
                <div @class([
                    'col-sm-6 col-12' => !in_array(
                        'state',
                        EcommerceHelper::getHiddenFieldsAtCheckout()),
                    'col-12' => in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()),
                ])>
                    <div class="form-group mb-3 @error('address.city') has-error @enderror">
                        @if (EcommerceHelper::useCityFieldAsTextField())
                            <div class="form-input-wrapper">
                                <input class="form-control" id="address_city" name="address[city]" autocomplete="city"
                                    type="text"
                                    value="{{ old('address.city', Arr::get($sessionCheckoutData, 'city')) }}"
                                    required>
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @else
                            <div class="select--arrow form-input-wrapper">
                                <select class="form-control" id="address_city" name="address[city]"
                                    autocomplete="city" data-type="city" data-using-select2="false"
                                    data-url="{{ route('ajax.cities-by-state') }}" required>
                                    <option value="">{{ __('Select city...') }}</option>
                                    @if (old('address.state', Arr::get($sessionCheckoutData, 'state')) ||
                                            in_array('state', EcommerceHelper::getHiddenFieldsAtCheckout()))
                                        @foreach (EcommerceHelper::getAvailableCitiesByState(old('address.state', Arr::get($sessionCheckoutData, 'state')), old('address.country', Arr::get($sessionCheckoutData, 'country'))) as $cityId => $cityName)
                                            <option value="{{ $cityId }}"
                                                @if (old('address.city', Arr::get($sessionCheckoutData, 'city')) == $cityId) selected @endif>{{ $cityName }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <x-core::icon name="ti ti-chevron-down" />
                                <label for="address_city">{{ __('City') }}</label>
                            </div>
                        @endif
                        {!! Form::error('address.city', $errors) !!}
                    </div>
                </div>
            @endif
        </div>

        {!! apply_filters('ecommerce_checkout_address_form_after_city_field', null, $sessionCheckoutData) !!}

        @if (!in_array('address', EcommerceHelper::getHiddenFieldsAtCheckout()))
            <div class="form-group mb-3 @error('address.address') has-error @enderror">
                <div class="form-input-wrapper">
                    <input class="form-control" id="address_address" name="address[address]" autocomplete="address"
                        type="text"
                        value="{{ old('address.address', Arr::get($sessionCheckoutData, 'address')) }}" required>
                    <label for="address_address">{{ __('Address') }}</label>
                </div>
                {!! Form::error('address.address', $errors) !!}
            </div>
        @endif

        @if (EcommerceHelper::isZipCodeEnabled())
            <div class="form-group mb-3 @error('address.zip_code') has-error @enderror">
                <div class="form-input-wrapper">
                    <input class="form-control" id="address_zip_code" name="address[zip_code]"
                        autocomplete="postal-code" type="text"
                        value="{{ old('address.zip_code', Arr::get($sessionCheckoutData, 'zip_code')) }}" required>
                    <label for="address_zip_code">{{ __('Zip code') }}</label>
                </div>
                {!! Form::error('address.zip_code', $errors) !!}
            </div>
        @endif
    </div>

    <!-- inicio Form Products -->
    <div class="products-wrapper" style="display:none;">
        @foreach ($products as $index => $product)
            <div class="product-item border rounded p-3 mb-4">
                <input type="hidden" class="product-id" value="{{ $product['id'] ?? null }}">
                <div class="row">
                    <div class="col-md-6">
                        <label>id:</label>
                        <input type="text" class="form-control product-id" value="{{ $product['id'] }}" required>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- fin Form products -->



    <!-- AQUI -->

    <div id="additional-info-wrapper" class="mb-3">
        <button type="button" id="toggle-additional-info" class="btn btn-primary w-100 text-start">
            <span id="toggle-icon" class="me-2">+</span>{{ __('Cotizador Envío') }}
        </button>
        <div id="additional-info-content" class="p-3 border rounded mt-2 d-none bg-light">
            <div id="quote-content">
                <p id="quote-message" class="text-info">{{ __('Your shipping quote will appear here.') }}</p>
            </div>
            <div id="additional-checkboxes"></div>
            <div id="additional-info-content" class="d-none"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {


            //asignacion valor si es direccion escrita o existente
            function handleAddressChange() {
                const selectedValue = $('#address_id').val(); // Obtiene el valor seleccionado del select
                const $inputValidate = $('#address_default_validate'); // Input relacionado

                if (selectedValue === 'new') {
                    console.log("new");
                    $inputValidate.val('1');
                } else {
                    console.log("else new");
                    $inputValidate.val('0');
                }
            }

            // Establece el valor inicial del select como "new" al cargar el DOM
            $('#address_id').val('new').trigger('change');

            // Agrega el evento al select para manejar cambios
            $('#address_id').on('change', handleAddressChange);




            $('#toggle-additional-info').on('click', function() {
                // Toggle the visibility of the content
                $('#quote-message').html(
                    '<span style="color: #93C47D;">Procesando su solicitud...</span>');
                $('#quote-content').html(
                        '<span style="color: #93C47D;">Procesando su solicitud...</span>');

                $('#additional-info-content').toggleClass('d-none');
                const isHidden = $('#additional-info-content').hasClass('d-none');
                $('#toggle-icon').text(isHidden ? '+' : '-');

                if (!isHidden) {


                    $valueAddress = $('#address_default_validate').val();

                    if ($valueAddress == 1) {
                        let hasError = false;

                        const fields = [{
                                id: '#address_name',
                                value: $('#address_name').val(),
                                label: 'Full Name'
                            },
                            {
                                id: '#address_email',
                                value: $('#address_email').val(),
                                label: 'Email'
                            },
                            {
                                id: '#address_state',
                                value: $('#address_state').val(),
                                label: 'State'
                            },
                            {
                                id: '#address_city',
                                value: $('#address_city').val(),
                                label: 'City'
                            },
                            {
                                id: '#address_address',
                                value: $('#address_address').val(),
                                label: 'Address'
                            },
                            {
                                id: '#address_zip_code',
                                value: $('#address_zip_code').val(),
                                label: 'Zip Code'
                            },
                            {
                                id: '#address_phone',
                                value: $('#address_phone').val(),
                                label: 'Phone'
                            }
                        ];

                        fields.forEach(field => {
                            if (!field.value) {
                                $(field.id).addClass('is-invalid');
                                hasError = true;
                            } else {
                                $(field.id).removeClass('is-invalid');
                            }
                        });

                        if (hasError) {
                            $('#quote-message').html(
                                '<span class="text-danger">Por favor complete todos los campos requeridos.</span>'
                            );
                            $('#quote-content').html(
                                '<span class="text-danger">Por favor complete todos los campos requeridos.</span>'
                            );


                            return;
                        }
                    }


                    // Gather form values and validate




                    let products = [];

                    // Iterar sobre cada producto en el formulario


                    $('.product-item').each(function() {
                        let product = {
                            id: $(this).find('.product-id').val(),
                            name: $(this).find('.product-name').val(),
                            sku: $(this).find('.product-sku').val(),
                            description: $(this).find('.product-description').val(),
                            price: $(this).find('.product-price').val(),
                            quantity: $(this).find('.product-quantity').val(),
                        };

                        products.push(product);
                    });

                    // Enviar la petición AJAX
                    $valueAddress = $('#address_default_validate').val();

                    if ($valueAddress == 1) {
                        console.log("if ")
                        console.log($('#address_zip_code').val());
                        $.ajax({
                            url: "{{ route('ruta.prueba') }}",
                            type: 'POST',
                            data: {
                                fullName: $('#address_name').val(),
                                email: $('#address_email').val(),
                                state: $('#address_state').val(),
                                city: $('#address_city').val(),
                                address: $('#address_address').val(),
                                zipCode: $('#address_zip_code').val(),
                                phone: $('#address_phone').val(),
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                products: products
                            },
                            success: function(response) {
                                console.log(response);

                                if (response.data.rates) {
                                    console.log(response.data.id)
                                    renderQuoteContent(response.data.rates, response.data.id);
                                }
                                $('#quote-message').html(
                                    `<span class="text-success">${response.message}</span>`);
                            },
                            error: function(xhr) {
                                $('#quote-message').html(
                                    `<span class="text-danger">Error: ${xhr.responseJSON.message}</span>`
                                );

                                $('#quote-content').html(
                                    `<span class="text-danger">Error: ${xhr.responseJSON.message}</span>`
                                );


                            }
                        });

                    } else {
                        console.log("else")
                        console.log($('#address_name_default').val());
                        console.log($('#address_email_default').val());
                        console.log($('#address_state_default').val());
                        console.log($('#address_city').val());
                        console.log($('#address_city_default').val());
                        console.log($('#address_zip_code_default').val());
                        console.log($('#address_phone_default').val());

                        $.ajax({
                            url: "{{ route('ruta.prueba') }}",
                            type: 'POST',
                            data: {
                                fullName: $('#address_name_default').val(),
                                email: $('#address_email_default').val(),
                                state: $('#address_state_default').val(),
                                city: $('#address_city').val(),
                                address: $('#address_city_default').val(),
                                zipCode: $('#address_zip_code_default').val(),
                                phone: $('#address_phone_default').val(),
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                products: products
                            },
                            success: function(response) {
                                console.log(response);

                                if (response.data.rates) {
                                    console.log(response.data.id)
                                    renderQuoteContent(response.data.rates, response.data.id);
                                }
                                $('#quote-message').html(
                                    `<span class="text-success">${response.message}</span>`);
                            },
                            error: function(xhr) {
                                $('#quote-message').html(
                                    `<span class="text-danger">Error: ${xhr.responseJSON.message}</span>`
                                );

                                $('#quote-content').html(
                                    `<span class="text-danger">Error: ${xhr.responseJSON.message}</span>`
                                );

                            }

                        });


                    }





                }
            });
        });





        let globalTotal = 0;
        let initialTotal = 0;

        function renderQuoteContent(rates, id) {
            const contentContainer = document.getElementById('quote-content');
            contentContainer.innerHTML = '';
            const totalTextElement = document.querySelector('.total-text');
            const priceTextElements = document.querySelectorAll('.shipping-price-text');
            initialTotal = parseFloat(totalTextElement.textContent.replace(/[^0-9.-]+/g, '')) ||
                0;

            function updateTotal(change) {
                globalTotal = initialTotal + change;
                totalTextElement.textContent = `$${globalTotal.toFixed(2)}`;
            }


            function updatePriceText(value) {
                priceTextElements.forEach((element) => {
                    element.textContent = `$${value.toFixed(2)}`;
                });
            }

            const availableRates = rates.filter(rate => rate.success);

            if (availableRates.length === 0) {
                contentContainer.innerHTML = '<p class="text-danger">No hay tarifas disponibles.</p>';
                return;
            }

            availableRates.forEach((rate, index) => {
                const rateCard = document.createElement('div');
                rateCard.className = 'mb-3 border p-3 rounded bg-white d-flex align-items-center';

                const checkboxId = `rate-${rate.id}`;
                const linkedCheckboxId = `linked-${rate.id}`;
                const totalCheckboxId = `total-${rate.id}`;

                rateCard.innerHTML = `
            <div class="form-check me-3">
                <input class="form-check-input rate-selection" type="checkbox" id="${checkboxId}" name="rateSelection" value="${rate.id}">
                <input type="checkbox" id="${linkedCheckboxId}" class="linked-checkbox d-none" name="quoteSelection" value="${id}">
                <input type="checkbox" id="${totalCheckboxId}" class="total-checkbox d-none" name="totalSelection" value="${rate.amount}">
            </div>
            <div>
                <h5 style="color: #93C47D;">${rate.provider_name}</h5>
                <p><strong>Días:</strong> ${rate.days ?? 'No especificado'}</p>
                <p><strong>Precio:</strong> ${rate.amount ?? 'No disponible'}</p>
            </div>
        `;


                const mainCheckbox = rateCard.querySelector(`#${checkboxId}`);
                const linkedCheckbox = rateCard.querySelector(`#${linkedCheckboxId}`);
                const totalCheckbox = rateCard.querySelector(`#${totalCheckboxId}`);

                mainCheckbox.addEventListener('change', (event) => {
                    if (event.target.checked) {

                        document.querySelectorAll('input.rate-selection').forEach((checkbox) => {
                            if (checkbox !== mainCheckbox) {
                                checkbox.checked = false;
                            }
                        });
                        document.querySelectorAll('input.linked-checkbox').forEach((checkbox) => {
                            if (checkbox !== linkedCheckbox) {
                                checkbox.checked = false;
                            }
                        });
                        document.querySelectorAll('input.total-checkbox').forEach((checkbox) => {
                            if (checkbox !== totalCheckbox) {
                                checkbox.checked = false;
                            }
                        });


                        linkedCheckbox.checked = true;
                        totalCheckbox.checked = true;

                        const selectionValue = parseFloat(totalCheckbox.value);
                        updateTotal(selectionValue);

                        updatePriceText(selectionValue);
                    } else {
                        linkedCheckbox.checked = false;
                        totalCheckbox.checked = false;


                        totalTextElement.textContent = `$${initialTotal.toFixed(2)}`;
                        updatePriceText(0);
                    }
                });

                contentContainer.appendChild(rateCard);
            });

            document.getElementById('additional-info-content').classList.remove('d-none');
        }


        $(document).ready(function() {
            // Interceptar el evento submit del formulario
            $('#checkout-form').on('submit', function(event) {
                event.preventDefault(); // Prevenir el envío del formulario hasta que las validaciones pasen

                let hasError = false;

                $addres = $('#address_default_validate').val();
                if ($addres == 1) {
                    // Lista de campos a validar
                    const fields = [{
                            id: '#address_name',
                            value: $('#address_name').val(),
                            label: 'Full Name',
                        },
                        {
                            id: '#address_email',
                            value: $('#address_email').val(),
                            label: 'Email',
                        },
                        {
                            id: '#address_state',
                            value: $('#address_state').val(),
                            label: 'State',
                        },
                        {
                            id: '#address_city',
                            value: $('#address_city').val(),
                            label: 'City',
                        },
                        {
                            id: '#address_address',
                            value: $('#address_address').val(),
                            label: 'Address',
                        },
                        {
                            id: '#address_zip_code',
                            value: $('#address_zip_code').val(),
                            label: 'Zip Code',
                        },
                        {
                            id: '#address_phone',
                            value: $('#address_phone').val(),
                            label: 'Phone',
                        },
                    ];



                    // Validar cada campo
                    fields.forEach((field) => {
                        if (!field.value) {
                            $(field.id).addClass('is-invalid'); // Agregar clase de error
                            hasError = true;
                        } else {
                            $(field.id).removeClass('is-invalid'); // Remover clase de error
                        }
                    });

                }

                // Guardar el botón para modificar su estado
                const submitButton = $('.payment-checkout-btn');

                // Deshabilitar el botón y mostrar el estado "Procesando"
                submitButton.prop('disabled', true);
                submitButton.html(
                    '<span class="spinner-border spinner-border-sm me-2"></span> Procesando. Espere por favor...'
                );


                // Validar que al menos un checkbox de rateSelection esté seleccionado
                const isRateSelected = $('input.rate-selection:checked').length > 0;
                if (!isRateSelected) {
                    alert('Por favor seleccione un método de envío.'); // Mostrar alerta
                    hasError = true;
                }

                // Mostrar mensaje de error si hay errores
                if (hasError) {
                    // Restablecer el botón a su estado normal
                    submitButton.prop('disabled', false);
                    submitButton.html('Verificar');
                    return; // Detener el envío del formulario
                }

                // Si no hay errores, enviar el formulario
                $('#quote-message').html('<span class="text-info">Procesando su solicitud...</span>');

                // Enviar el formulario manualmente
                this.submit();
            });
        });
    </script>
    <style>
        #toggle-additional-info {
            font-weight: bold;
            border: none;
            background: #93C47D;
            color: black;
            border-radius: 5px;
            transition: background 0.3s, transform 0.3s;
        }

        #toggle-additional-info:hover {
            background: #93C47D;
            transform: scale(1.02);
        }

        #additional-info-content {
            animation: fadeIn 0.3s ease-in-out;
        }

        .text-success {
            color: #93C47D;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <!-- AQUI -->
    @if (!auth('customer')->check())
        <div id="register-an-account-wrapper">
            <div class="mb-3 form-group">
                <input id="create_account" name="create_account" type="checkbox" value="1"
                    @if (old('create_account') == 1) checked @endif>
                <label class="form-label"
                    for="create_account">{{ __('Register an account with above information?') }}</label>
            </div>

            <div class="password-group @if (!$errors->has('password') && !$errors->has('password_confirmation')) d-none @endif">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="form-group  @error('password') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input class="form-control" id="password" name="password" type="password"
                                    autocomplete="new-password">
                                <label for="password">{{ __('Password') }}</label>
                            </div>
                            {!! Form::error('password', $errors) !!}
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group @error('password_confirmation') has-error @enderror">
                            <div class="form-input-wrapper">
                                <input class="form-control" id="password-confirm" name="password_confirmation"
                                    type="password" autocomplete="password-confirmation">
                                <label for="password-confirm">{{ __('Password confirmation') }}</label>
                            </div>
                            {!! Form::error('password_confirmation', $errors) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! apply_filters('ecommerce_checkout_address_form_after', null, $sessionCheckoutData) !!}
</div>
@endif
