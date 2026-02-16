<!--
dashboard seetinfs page
- extends layout "layouts/adminLayout"
- displays an editable form of the settings record
- check the "AdminController" in the method "editSettings"
-->
@extends('layouts.adminLayout')

@php
    $record = $model;
@endphp

@section('content')
    <x-ui.card>

        <x-slot:header>
            <div class="flex gap-2">
                <h1>Settings</h1>
            </div>
        </x-slot:header>

        <x-slot:content>

            <x-ui.form method="PATCH" action="{{ route('admin.action.action.update_settings') }}">

                <x-ui.form-group>
                    <x-ui.error key="business_name" />
                    <x-ui.label for="f_business_name">Business Name</x-ui.label>
                    <x-ui.input id="f_business_name" name="business_name" type="text" value="{{ $record->business_name }}"
                        placeholder="business name" minLength="3" maxLength="64" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="business_description" />
                    <x-ui.label for="f_business_description">Business Description</x-ui.label>
                    <x-ui.input id="f_business_description" name="business_description" type="text"
                        value="{{ $record->business_description }}" placeholder="business description" minLength="0"
                        maxLength="128" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="business_email" />
                    <x-ui.label for="f_business_email">Business email</x-ui.label>
                    <x-ui.input id="f_business_email" name="business_email" type="email"
                        value="{{ $record->business_email }}" placeholder="business email" minLength="10" maxLength="64" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="business_phone_number" />
                    <x-ui.label for="f_business_phone_number">Business Phone Number</x-ui.label>
                    <x-ui.input id="f_business_phone_number" name="business_phone_number" type="text"
                        value="{{ $record->business_phone_number }}" placeholder="business phone number" minLength="10"
                        maxLength="64" />
                </x-ui.form-group>

                <x-ui.form-group>
                    <x-ui.error key="business_addresses" />
                    <x-ui.label for="f_business_addresses">Business Addresses</x-ui.label>
                    <x-ui.input id="f_business_addresses" name="business_addresses" type="text"
                        value="{{ $record->business_addresses }}" placeholder="address 1, address 2, ..." minLength="0"
                        maxLength="256" />
                </x-ui.form-group>

                <x-ui.form-actions>
                    <x-ui.button type="reset" variant="outline">
                        Reset
                    </x-ui.button>
                    <x-ui.button type="submit">
                        Update
                    </x-ui.button>
                </x-ui.form-actions>

            </x-ui.form>

        </x-slot:content>

        <x-slot:footer>
            <div class="flex">
                <p>Last updated: {{ $record->updated_at->diffForHumans() }}</p>
            </div>
        </x-slot:footer>

    </x-ui.card>
@endsection
