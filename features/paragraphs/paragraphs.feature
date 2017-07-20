Feature: Check paragraphs for site

  As administrator user
  I should see paragraphs config page.

  @api
  Scenario: Administrator should see paragraphs and edit them.
  Given I am logged in dashboard as a user with the "administrator" role

  When I go to "admin/structure"
  Then I should get a 200 HTTP response
  And I should see the text "Paragraphs types"

  When I go to "admin/structure/paragraphs_type"
  Then I should see the text "free_dataset"
  And I should see the text "free_values"
  And I should see the text "location"
  And I should see the text "monthly_dataset"
  And I should see the text "monthly_values"
  And I should see the text "pointer_on_map_dataset"
  And I should see the text "regional_dataset"
  And I should see the text "regional_values"
  And I should see the text "target"

  # Location.
  When I go to "admin/structure/paragraphs_type/location/fields"
  Then I should see the text "field_location_coordinates"
  And I should see the text "Geofield"
  And I should see the text "field_location_name"

  # Free dataset.
  When I go to "admin/structure/paragraphs_type/free_dataset/fields"
  Then I should see the text "field_free_values"

  # Free values.
  When I go to "admin/structure/paragraphs_type/free_values/fields"
  Then I should see the text "field_key"
  And I should see the text "field_decimal_value"

  # Monthly dataset.
  When I go to "admin/structure/paragraphs_type/monthly_dataset/fields"
  Then I should see the text "field_monthly_values"

  # Monthly values.
  When I go to "admin/structure/paragraphs_type/monthly_values/fields"
  Then I should see the text "field_group"
  And I should see the text "field_monthly_value"
  And I should see the text "field_year"

  # Pointer on map.
  When I go to "admin/structure/paragraphs_type/pointer_on_map_dataset/fields"
  Then I should see the text "field_location"

  # Regional dataset.
  When I go to "admin/structure/paragraphs_type/regional_dataset/fields"
  Then I should see the text "field_regional_values"

  # Regional values.
  When I go to "admin/structure/paragraphs_type/regional_values/fields"
  Then I should see the text "field_color"
  And I should see the text "field_region"
  And I should see the text "field_decimal_value"

  # Target.
  When I go to "admin/structure/paragraphs_type/target/fields"
  Then I should see the text "field_target_description"
  And I should see the text "field_target_label"
  And I should see the text "field_target_values"
  And I should see the text "field_target_year"

  @api
  Scenario: Anonymous user should not see paragraphs settings page.
  Given I am an anonymous user
  When I go to "admin/structure"
  Then I should get a 403 HTTP response
