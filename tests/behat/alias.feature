@local_alias @javascript
Feature: Manage aliases

  Background:
    When I log in as "admin"
    And I navigate to "Plugins > Manage alias" in site administration

    And I press "Create url"
    And I set the field "friendly" to "http://localhost/1"
    And I set the field "destination" to "http://localhost/course.php?id=1"
    And I press "Save changes"

    And I press "Create url"
    And I set the field "friendly" to "http://localhost/2"
    And I set the field "destination" to "http://localhost/course.php?id=2"
    And I press "Save changes"

    And I press "Create url"
    And I set the field "friendly" to "http://localhost/3"
    And I set the field "destination" to "http://localhost/course.php?id=3"
    And I press "Save changes"

    And I press "Create url"
    And I set the field "friendly" to "http://localhost/4"
    And I set the field "destination" to "http://localhost/course.php?id=4"
    And I press "Save changes"

  @javascript
  Scenario: Creating and editing alias
    When I log in as "admin"
    And I navigate to "Plugins > Manage alias" in site administration
    And I should see "http://localhost/course.php?id=1" in the "http://localhost/1" "table_row"
    And I press "Edit url"
    And I set the field "friendly" to "http://localhost/edited"
    And I set the field "destination" to "http://localhost/course.php?id=999"
    And I press "Save changes"
    And I should see "http://localhost/course.php?id=999" in the "http://localhost/edited" "table_row"
    And I log out

  @javascript
  Scenario: Creating and deleting alias
    When I log in as "admin"
    And I navigate to "Plugins > Manage alias" in site administration
    And I should see "http://localhost/course.php?id=1" in the "http://localhost/1" "table_row"
    And I press "Delete url"
    And I click on "Delete url" "button" in the "Delete url alias" "dialogue"
    Then I should not see "http://localhost/frontendmasters"
    And I log out

  @javascript
  Scenario: Creating and searching for an alias
    When I log in as "admin"
    And I navigate to "Plugins > Manage alias" in site administration
    And I set the field "query" to "1"
    And I press "Filter"
    And I should see "http://localhost/course.php?id=1" in the "http://localhost/1" "table_row"
    Then I should not see "URL alias not found."
    And I log out

  @javascript
  Scenario: Creating and select page 2
    When I log in as "admin"
    And I navigate to "Plugins > Manage alias" in site administration
    And I should see "http://localhost/course.php?id=1" in the "http://localhost/1" "table_row"
    Then I should not see "URL alias not found."
    And I click on "//li[@data-page-number='2']" "xpath_element"
    And I should see "http://localhost/course.php?id=4" in the "http://localhost/4" "table_row"
    And I log out
