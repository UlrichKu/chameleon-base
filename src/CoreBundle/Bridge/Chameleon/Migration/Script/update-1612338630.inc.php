<h1>Build #1612338630</h1>
<h2>Date: 2021-02-03</h2>
<div class="changelog">
    - #638: Deprecate menu item position
</div>
<?php

$data = TCMSLogChange::createMigrationQueryData('cms_tbl_display_orderfields', 'de')
  ->setWhereEquals([
      'id' => '9f22963e-2768-8e9e-2e33-4f7d0018f4c1',
  ])
;
TCMSLogChange::delete(__LINE__, $data);

$data = TCMSLogChange::createMigrationQueryData('cms_field_conf', 'de')
  ->setFields([
      '049_helptext' => 'Deprecated seit 7.0.12 - jetzt alphabetisch sortiert.',
  ])
  ->setWhereEquals([
      'id' => TCMSLogChange::GetTableFieldId(TCMSLogChange::GetTableId('cms_menu_item'), 'position'),
  ])
;
TCMSLogChange::update(__LINE__, $data);

$data = TCMSLogChange::createMigrationQueryData('cms_field_conf', 'en')
    ->setFields([
        '049_helptext' => 'Deprecated as of 7.0.12 - is now sorted alphabetically.',
    ])
    ->setWhereEquals([
        'id' => TCMSLogChange::GetTableFieldId(TCMSLogChange::GetTableId('cms_menu_item'), 'position'),
    ])
;
TCMSLogChange::update(__LINE__, $data);

$query ="ALTER TABLE `cms_menu_item`
                     CHANGE `position`
                            `position` INT NOT NULL COMMENT 'Position: Deprecated as of 7.0.12 - is now sorted alphabetically.'";
TCMSLogChange::RunQuery(__LINE__, $query);

