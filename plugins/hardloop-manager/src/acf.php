<?php
// Load this file from hardloop-manager.php via require_once

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group([
  'key' => 'group_loper_geg',
  'title' => 'Loper-gegevens',
  'fields' => [
    [ 'key'=>'field_voornaam','label'=>'Voornaam','name'=>'voornaam','type'=>'text' ],
    [ 'key'=>'field_achternaam','label'=>'Achternaam','name'=>'achternaam','type'=>'text' ],
    [ 'key'=>'field_email','label'=>'E-mail','name'=>'email','type'=>'email' ],
    [ 'key'=>'field_geboortedatum','label'=>'Geboortedatum','name'=>'geboortedatum','type'=>'date_picker' ],
    [ 'key'=>'field_noodnummer','label'=>'Noodnummer','name'=>'noodnummer','type'=>'text' ],
    [ 'key'=>'field_loopervaring','label'=>'Loopervaring','name'=>'loopervaring','type'=>'textarea' ],
    [ 'key'=>'field_gewenst_pace','label'=>'Gewenst pace','name'=>'gewenst_pace','type'=>'text' ],
    [ 'key'=>'field_doel_2025','label'=>'Doel 2025','name'=>'doel_2025','type'=>'textarea' ],
    [ 'key'=>'field_shirt','label'=>'Shirtmaat','name'=>'shirt','type'=>'text' ],
    [ 'key'=>'field_blessures','label'=>'Blessures','name'=>'blessures','type'=>'textarea' ],
    [ 'key'=>'field_opmerkingen','label'=>'Opmerkingen','name'=>'opmerkingen','type'=>'textarea' ],
    [ 'key'=>'field_trainer_opmerkingen','label'=>'Trainer opmerkingen','name'=>'trainer_opmerkingen','type'=>'textarea' ],
    [ 'key'=>'field_niveau','label'=>'Niveau','name'=>'niveau','type'=>'select','choices'=>['beginner'=>'Beginner','gevorderd'=>'Gevorderd','expert'=>'Expert'] ],
    [ 'key'=>'field_actief',   'label'=>'Actief',   'name'=>'actief',   'type'=>'true_false',    'ui'=>1 ],
    +  [ 'key'=>'field_gestart',  'label'=>'Gestart datum','name'=>'gestart', 'type'=>'date_picker',   'display_format'=>'d/m/Y','return_format'=>'Y-m-d' ],
    +  [ 'key'=>'field_gestopt',  'label'=>'Gestopt datum','name'=>'gestopt', 'type'=>'date_picker',   'display_format'=>'d/m/Y','return_format'=>'Y-m-d' ],
    
 ],
  'location' => [[ [ 'param'=>'post_type','operator'=>'==','value'=>'loper' ] ]],
  'menu_order'=>0,'position'=>'normal','style'=>'default','label_placement'=>'top','instruction_placement'=>'label',
]);

endif;