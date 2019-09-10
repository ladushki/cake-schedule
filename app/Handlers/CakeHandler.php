<?php


namespace App\Handlers;


use App\Exceptions\WrongFormat;
use App\Services\CakeDayService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CakeHandler
{

    /**
     * @var CakeDayService
     */
    private $service;

    public function __construct(CakeDayService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $birthdays
     *
     * @return array
     */
    public function getCakeDates(array $birthdays): array
    {
        $cakes = $this->countPeople($birthdays);
        $dates = array_keys($cakes);
        sort($dates);
        $datesCollection = collect($dates);

        $cakesDates = [];

        $index = 0;
        foreach ($cakes as $date => $number) {
            $previousDate = $datesCollection[$index - 1] ?? '';
            $nextDay      = $datesCollection[$index + 1] ?? '';

            $wasCakeDate    = $this->checkIfHadCake($previousDate, $date);
            $willBeCakeDate = $this->checkIfWillHaveCake($nextDay, $date);

            if (empty($willBeCakeDate) && empty($wasCakeDate)) {
                $cakesDates[$date] = $number;
            } elseif (!empty($willBeCakeDate)) {
                $cakesDates[$nextDay] = $number + ($cakes[$nextDay] ?? 0);
            } elseif ($wasCakeDate && !empty($cakesDates[$previousDate])) {
                $cakesDates[$date] = 0;

                $cakesDates[Carbon::parse($date)->addDay()->format('Y-m-d')] = $number;
            }
            $index++;
        };

        return $cakesDates;
    }

    /**
     * @param array $birthdays
     *
     * @return Collection
     */
    public function run(array $birthdays): Collection
    {
        $cakesDates = $this->getCakeDates($birthdays);

        return collect(array_filter($cakesDates))->map(
            function ($number, $date) {
                return implode(', ', [$date, $number < 2 ? $number : 0, $number > 1 ? 1 : 0, $number]);
            }
        )->values();
    }


    /**
     * @param string $previousDate
     * @param string $date
     *
     * @return bool
     */
    protected function checkIfHadCake(string $previousDate, string $date): bool
    {
        $hadCake = false;
        if (!empty($previousDate)) {
            $hadCake = Carbon::parse($date)->subDay()->isSameAs('Y-m-d', $previousDate);
        }

        return $hadCake;
    }


    /**
     * @param string $nextDay
     * @param string $date
     *
     * @return bool
     */
    protected function checkIfWillHaveCake(string $nextDay, string $date): bool
    {
        $willBeCake = false;
        if (!empty($nextDay)) {
            $willBeCake = Carbon::parse($date)->addDay()->isSameAs('Y-m-d', $nextDay);
        }

        return $willBeCake;
    }

    /**
     * @param array $birthdays
     *
     * @return array
     */
    private function countPeople(array $birthdays): array
    {
        $cakes = [];
        foreach ($birthdays as $line) {
            $person         = explode(', ', $line);
            if (empty($person[1])) {
                throw new WrongFormat('No date');
            }
            $birthday       = $this->service->getBirthday($person[1]);
            $nextWorkingDay = $this->service->getNextWorkingDay($birthday);
            if (!isset($cakes[$nextWorkingDay])) {
                $cakes[$nextWorkingDay] = 0;
            }
            ++$cakes[$nextWorkingDay];
        }

        return $cakes;
    }
}
