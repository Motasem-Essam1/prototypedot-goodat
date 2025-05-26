<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Attribute::query()->create([
            'key' => 'email',
            'value' => 'deftat@deftat.com'
        ]);

        Attribute::query()->create([
            'key' => 'phone_number',
            'value' => '353123456789'
        ]);

        Attribute::query()->create([
            'key' => 'location',
            'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4761.778295030249!2d-8.228263422828276!3d53.363138777347565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4859bae45c4027fb%3A0xcf7c1234cedbf408!2sIreland!5e0!3m2!1sen!2seg!4v1679482625467!5m2!1sen!2seg'
        ]);

        Attribute::query()->create([
            'key' => 'address',
            'value' => '1908 Timarron Way, Naples, FL, 34109'
        ]);

        Attribute::query()->create([
            'key' => 'contact_us_description',
            'value' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia, molestiae quas vel sint commodi repudiandae consequuntur voluptatum laborum numquam blanditiis harum quisquam eius sed odit fugiat iusto fuga praesentium optio, eaque rerum! Provident similique accusantium nemo autem.'
        ]);

        Attribute::query()->create([
            'key' => 'contact_us_questions_list',
            'value' => 'Less than 12-hour response to your question. | Plan of action summarized in an email follow up.'
        ]);

        Attribute::query()->create([
            'key' => 'facebook',
            'value' => 'https://www.facebook.com'
        ]);

        Attribute::query()->create([
            'key' => 'instagram',
            'value' => 'https://www.instagram.com'
        ]);

        Attribute::query()->create([
            'key' => 'youtube',
            'value' => 'https://www.youtube.com'
        ]);

        Attribute::query()->create([
            'key' => 'ios_app',
            'value' => 'https://www.apple.com'
        ]);

        Attribute::query()->create([
            'key' => 'android_app',
            'value' => 'https://play.google.com'
        ]);
    }
}
