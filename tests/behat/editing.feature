@tool @tool_albertolarah
Feature: Creating, editing and deleting entries
  In order to manage entries
  As a teacher
  I need to be able to add, edit and delete entries

  Background:
    Given the following "users" exist:
      | username | firstname | lastname   | email                |
      | teacher1 | Teacher   | Lastname 1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | course1   | weeks  |
    And the following "course enrolments" exist:
      | user     | course  | role           |
      | teacher1 | course1 | editingteacher |

  @javascript
  Scenario: Add and edit an entry
    When I log in as "teacher1"
    And I follow "course1"
    And I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add new entry"
    And I set the following fields to these values:
      | Name      | Entry to be updated |
      | Already completed? | 0            |
    And I press "Save changes"
    Then the following should exist in the "tool_albertolarah_table" table:
      | Name         | Completed |
      | Entry to be updated | No        |
    And I click on ".edit-entry" "css_element" in the "Entry to be updated" "table_row"
    And I set the following fields to these values:
      | Already completed? | 1 |
    And I press "Save changes"
    And the following should exist in the "tool_albertolarah_table" table:
      | Name         | Completed |
      | Entry to be updated | Yes       |
    And I log out

  Scenario: Delete an entry
    Given I am on homepage
    When I log in as "teacher1"
    And I follow "course1"
    And I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add new entry"
    And I set the field "Name" to "I will be remove this entry"
    And I press "Save changes"
    And I follow "Add new entry"
    And I set the field "Name" to "test entry 2"
    And I press "Save changes"
    And I click on ".delete-entry" "css_element" in the "I will be remove this entry" "table_row"
    Then I should see "test entry 2"
    And I should not see "I will be remove this entry"
    And I log out

