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
     * @testdox    It applies explicit filters.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_applies_explicit_filters($request, Builder $query)
    {
        $filters = [
            'foo'         => 'ciao',
            'bar'         => '0',
            'baz'         => '',
            'foo-bar_baz' => 'null',
            'test'        => null,
        ];

        $appliedFilters = [
            'foo'         => 'ciao',
            'bar'         => '0',
            'foo-bar_baz' => 'null',
        ];

        $request->all()->willReturn($filters);

        $this->applyToQuery($query)->shouldReturn($query);

        $this->filters->shouldBe($appliedFilters);
    }

    /**
     * @testdox    It applies registered implicit filters.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_applies_registered_implicit_filters($request, Builder $query)
    {
        $filters = [
            'foo'      => 'ciao',
            'bar'      => '0',
            'baz'      => '',
            'implicit' => '',
        ];

        $appliedFilters = [
            'foo'      => 'ciao',
            'bar'      => '0',
            'implicit' => '',
        ];

        $request->all()->willReturn($filters);

        $this->applyToQuery($query)->shouldReturn($query);

        $this->filters->shouldBe($appliedFilters);
    }

    /**
     * @testdox    It does not apply non existent filters.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function it_does_not_apply_non_existent_filters($request, Builder $query)
    {
        $filters = [
            'foo'          => 'ciao',
            'bar'          => '0',
            'non_existent' => '',
            'foo-bar_baz'  => 'null',
        ];

        $appliedFilters = [
            'foo'         => 'ciao',
            'bar'         => '0',
            'foo-bar_baz' => 'null',
        ];

        $request->all()->willReturn($filters);

        $this->applyToQuery($query)->shouldReturn($query);

        $this->filters->shouldBe($appliedFilters);
    }
}
