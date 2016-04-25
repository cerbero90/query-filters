<?php

namespace spec\Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TestFiltersSpec extends ObjectBehavior
{
    /**
     * Initialise the test.
     *
     * @author    Andrea Marco Sartori
     * @param    Illuminate\Http\Request    $request
     * @return    void
     */
    public function let(Request $request)
    {
        $this->beConstructedWith($request);
    }

    public function it_is_initializable(Request $request)
    {
        $this->shouldHaveType('Cerbero\QueryFilters\TestFilters');
    }

    /**
     * @testdox    It applies all the filters to the given query.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_applies_all_the_filters_to_the_given_query($request, Builder $query)
    {
        $filters = [
            'foo'         => 'ciao',
            'bar'         => '0',
            'baz'         => '',
            'foo-bar_baz' => '',
        ];

        $request->all()->willReturn($filters);

        $this->applyToQuery($query)->shouldReturn($query);

        $this->filters->shouldBe($filters);
    }
}
