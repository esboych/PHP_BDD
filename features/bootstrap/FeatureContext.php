<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I'm on the main page
     */
    public function iMOnTheMainPage()
    {
        $path="/";
        $this->visitPath($path);
    }

    /**
     * @When I choose the Dresden-Nürnberg route
     */
    public function iChooseTheDresdenNurnbergRoute()
    {
        /* use js here as built-in css actions doesn't work for some reason */
        $javascript = "document.getElementById('DeparturePoint').value='Dresden'";
        $this->getSession()->executeScript($javascript);
        $javascript = "document.getElementById('ArrivalPoint').value='Nürnberg'";
        $this->getSession()->executeScript($javascript);
    }


    /**
     * @When I pick the date of :arg1
     */
    public function iPickTheDateOf($date)
    {
        $this->getSession()->getPage()->find("css", "input[name='rideDate']")->setValue("$date");
    }

    /**
     * @When I add a kid to the trip
     */
    public function iAddAKidToTheTrip()
    {
        $siblingElement = $this->getSession()->getPage()->find("css","input#children.quantity__input");
        $parentElement = $siblingElement->getParent();
        $element = $parentElement->find("css", "input.quantity__button-plus.js-quantity__button-plus");
        $element->click();
    }

    /**
     * @When I start the search for one-way ride
     */
    public function iStartTheSearchForOneWayRide()
    {
        /* first uncheck the zuruck checkbox */
        $checkBox = $this->getSession()->getPage()->find("css","input#backRide");
        $checkBox->uncheck();
        /* then press the search button */
        $element = $this->getSession()->getPage()->find("css","input.search-form__btn-search");
        $element->click();
    }

    /**
     * @When I pick the second result
     */
    public function iPickTheSecondResult()
    {
        $this->getSession()->executeScript("window.scrollBy(0,250)", "");
        $parentElement = $this->getSession()->getPage()->find("css","#search-result-direct");
        $elements = $parentElement->findAll("css", ".ride-item-pair");

        /* iterate over results to pick the 2nd */
        $counter = 0;
        foreach ($elements as $elem) {
            if ($counter > 0) {
                $btn = $elem->find("css", "input.reserve");
                $btn->submit();
                break;
            }
            $counter++;
        }
    }

    /**
     * @Then I can check the product is in the bag
     */
    public function iCanCheckTheProductIsInTheBag()
    {
        $element = $this->getSession()->getPage()->find("css","span.price-detail");
        $text = $element->getText();

        /* check the ride is booked for one Adult and one Kid */
        expect($text)->toBe("1 Erwachsener, 1 Kind");

        /* check that ordering button is active i.e. do not have btn-disabled class */
        assert($this->getSession()->getPage()->find("css","a#book-button")->hasClass("btn-disabled"));
    }

}
