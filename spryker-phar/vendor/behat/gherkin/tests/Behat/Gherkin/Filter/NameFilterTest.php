<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Tests\Behat\Gherkin\Filter;

use Behat\Gherkin\Filter\NameFilter;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioNode;
use PHPUnit_Framework_TestCase;

class NameFilterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testFilterFeature()
    {
        $feature = new FeatureNode('feature1', null, [], null, [], null, null, null, 1);
        $filter = new NameFilter('feature1');
        $this->assertSame($feature, $filter->filterFeature($feature));

        $scenarios = [
            new ScenarioNode('scenario1', [], [], null, 2),
            $matchedScenario = new ScenarioNode('scenario2', [], [], null, 4),
        ];
        $feature = new FeatureNode('feature1', null, [], null, $scenarios, null, null, null, 1);
        $filter = new NameFilter('scenario2');
        $filteredFeature = $filter->filterFeature($feature);

        $this->assertSame([$matchedScenario], $filteredFeature->getScenarios());
    }

    /**
     * @return void
     */
    public function testIsFeatureMatchFilter()
    {
        $feature = new FeatureNode('random feature title', null, [], null, [], null, null, null, 1);

        $filter = new NameFilter('feature1');
        $this->assertFalse($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('feature1', null, [], null, [], null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('feature1 title', null, [], null, [], null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feature1 title', null, [], null, [], null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feature title', null, [], null, [], null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));

        $filter = new NameFilter('/fea.ure/');
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feaSure title', null, [], null, [], null, null, null, 1);
        $this->assertTrue($filter->isFeatureMatch($feature));

        $feature = new FeatureNode('some feture title', null, [], null, [], null, null, null, 1);
        $this->assertFalse($filter->isFeatureMatch($feature));
    }

    /**
     * @return void
     */
    public function testIsScenarioMatchFilter()
    {
        $filter = new NameFilter('scenario1');

        $scenario = new ScenarioNode('UNKNOWN', [], [], null, 2);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('scenario1', [], [], null, 2);
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('scenario1 title', [], [], null, 2);
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $scenario = new ScenarioNode('some scenario title', [], [], null, 2);
        $this->assertFalse($filter->isScenarioMatch($scenario));

        $filter = new NameFilter('/sce.ario/');
        $this->assertTrue($filter->isScenarioMatch($scenario));

        $filter = new NameFilter('/scen.rio/');
        $this->assertTrue($filter->isScenarioMatch($scenario));
    }

}
