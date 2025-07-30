<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Unit tests for the manage_aliases class.
 *
 * @package   local_friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_friendly_url;
use advanced_testcase;
use manage_aliases;

defined ('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot .'/local/friendly_url/lib.php');
require_once($CFG->dirroot .'/local/friendly_url/classes/manage_aliases.php');

/**
 * Unit tests for the manage_aliases class.
 *
 * @package   local_friendly_url
 * @copyright 2025, Abeeha Khan
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class friendly_url_manager_test extends advanced_testcase {
    /**
     * Test that we can create an alias.
     * @covers \local_friendly_url\friendly_url_manager::create_alias
     * @return void
     */
    public function test_create_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();
        $aliases = $manager->get_alias(0, '');
        $this->assertEmpty($aliases['aliases']);

        $result = $manager->create_alias('http://localhost/frontendmasters', 'http://localhost/course.php?id=99');
        $this->assertTrue($result);
        $aliases = $manager->get_alias(0, '');
        $this->assertNotEmpty($aliases);

        $this->assertCount(1, $aliases['aliases']);
        $alias = array_pop($aliases['aliases']);

        $this->assertEquals('http://localhost/frontendmasters', $alias->friendly);
        $this->assertEquals('http://localhost/course.php?id=99', $alias->destinationurl);
    }

    /**
     * Test that we can update an alias.
     * @covers \local_friendly_url\friendly_url_manager::update_alias
     * @return void
     */
    public function test_update_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        $manager->create_alias('http://localhost/frontendmaster', 'http://localhost/course.php?id=99');
        $aliases = $manager->get_alias(0, '');
        $alias = array_pop($aliases['aliases']);

        $manager->update_alias($alias->id, 'http://localhost/editedalias', 'http://localhost/course.php?id=999');
        $updatedalias = $manager->get_alias_by_url($alias->id);

        $this->assertEquals('http://localhost/editedalias', $updatedalias->friendly);
        $this->assertEquals('http://localhost/course.php?id=999', $updatedalias->destinationurl);
    }

    /**
     * Test that we can delete an alias.
     * @covers \local_friendly_url\friendly_url_manager::delete_alias
     * @return void
     */
    public function test_delete_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        $manager->create_alias('http://localhost/frontendmaster', 'http://localhost/course.php?id=99');
        $aliases = $manager->get_alias(0, '');
        $this->assertCount(1, $aliases['aliases']);
        $alias = array_pop($aliases['aliases']);

        $result = $manager->delete_alias($alias->id);
        $this->assertTrue($result);

        $this->assertFalse($manager->get_alias_by_url($alias->id));
        $this->assertEmpty($aliases['aliases']);
    }

    /**
     * Test that we can get an alias by id.
     * @covers \local_friendly_url\friendly_url_manager::get_alias_by_id
     * @return void
     */
    public function test_get_alias_by_id(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        $manager->create_alias('http://localhost/frontendmasters', 'http://localhost/course.php?id=99');
        $aliases = $manager->get_alias(0, '');
        $alias = array_pop($aliases['aliases']);

        $result = $manager->get_alias_by_url($alias->id);

        $this->assertEquals('http://localhost/frontendmasters', $result->friendly);
        $this->assertEquals('http://localhost/course.php?id=99', $result->destinationurl);
    }

    /**
     * Test that we can search for an alias.
     * @covers \local_friendly_url\friendly_url_manager::search_alias
     * @return void
     */
    public function test_search_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        $manager->create_alias('http://localhost/frontendmasters', 'http://localhost/course.php?id=99');
        $aliases = $manager->get_alias(0, '');
        $this->assertNotEmpty($aliases);
        $this->assertCount(1, $aliases['aliases']);
        $alias = array_pop($aliases['aliases']);

        $this->assertEquals('http://localhost/frontendmasters', $alias->friendly);
        $this->assertEquals('http://localhost/course.php?id=99', $alias->destinationurl);
    }

    /**
     * Test that we can get an alias with pagination.
     * @covers \local_friendly_url\friendly_url_manager::test_pagination_alias
     * @return void
     */
    public function test_pagination_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        for ($i = 1; $i <= 7; $i++) {
            $manager->create_alias("http://localhost/{$i}", "http://localhost/course.php?id={$i}");
        }

        $aliasespage0 = $manager->get_alias(0, '');
        $this->assertNotEmpty($aliasespage0);
        $this->assertCount(3, $aliasespage0['aliases']);
        $this->assertEquals(0, $aliasespage0['page']);
        $this->assertEquals(3, $aliasespage0['pages']);
        $this->assertEquals(7, $aliasespage0['count']);

        $aliasespage1 = $manager->get_alias(1, '');
        $this->assertNotEmpty($aliasespage1);
        $this->assertCount(3, $aliasespage1['aliases']);
        $this->assertEquals(1, $aliasespage1['page']);
        $this->assertEquals(3, $aliasespage1['pages']);
        $this->assertEquals(7, $aliasespage1['count']);

        $aliasespage2 = $manager->get_alias(2, '');
        $this->assertNotEmpty($aliasespage2);
        $this->assertCount(1, $aliasespage2['aliases']);
        $this->assertEquals(2, $aliasespage2['page']);
        $this->assertEquals(3, $aliasespage2['pages']);
        $this->assertEquals(7, $aliasespage2['count']);

        $this->assertEquals('http://localhost/1', $aliasespage0['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=1', $aliasespage0['aliases'][0]->destinationurl);

        $this->assertEquals('http://localhost/4', $aliasespage1['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=4', $aliasespage1['aliases'][0]->destinationurl);

        $this->assertEquals('http://localhost/7', $aliasespage2['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=7', $aliasespage2['aliases'][0]->destinationurl);
    }

    /**
     * Test that we can search for an alias with pagination.
     * @covers \local_friendly_url\friendly_url_manager::search_pagination_alias
     * @return void
     */
    public function test_search_pagination_alias(): void {
        $this->resetAfterTest();
        $this->setUser(2);
        $manager = new manage_aliases();

        for ($i = 1; $i <= 7; $i++) {
            $manager->create_alias("http://localhost/esaka{$i}", "http://localhost/course.php?id={$i}");
        }

        $aliasespage0 = $manager->get_alias(0, 'esaka');
        $this->assertNotEmpty($aliasespage0);
        $this->assertCount(3, $aliasespage0['aliases']);
        $this->assertEquals(0, $aliasespage0['page']);
        $this->assertEquals(3, $aliasespage0['pages']);
        $this->assertEquals(7, $aliasespage0['count']);

        $aliasespage1 = $manager->get_alias(1, 'esaka');
        $this->assertNotEmpty($aliasespage1);
        $this->assertCount(3, $aliasespage1['aliases']);
        $this->assertEquals(1, $aliasespage1['page']);
        $this->assertEquals(3, $aliasespage1['pages']);
        $this->assertEquals(7, $aliasespage1['count']);

        $aliasespage2 = $manager->get_alias(2, 'esaka');
        $this->assertNotEmpty($aliasespage2);
        $this->assertCount(1, $aliasespage2['aliases']);
        $this->assertEquals(2, $aliasespage2['page']);
        $this->assertEquals(3, $aliasespage2['pages']);
        $this->assertEquals(7, $aliasespage2['count']);

        $this->assertEquals('http://localhost/esaka1', $aliasespage0['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=1', $aliasespage0['aliases'][0]->destinationurl);

        $this->assertEquals('http://localhost/esaka4', $aliasespage1['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=4', $aliasespage1['aliases'][0]->destinationurl);

        $this->assertEquals('http://localhost/esaka7', $aliasespage2['aliases'][0]->friendly);
        $this->assertEquals('http://localhost/course.php?id=7', $aliasespage2['aliases'][0]->destinationurl);
    }
}
