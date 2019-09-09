<?php declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;

class CakeDayService
{

    /**
     * @var array
     */
    protected $holidays = [
        '12-25',
        '12-26',
        '01-01',
    ];

    /**
     * @param string $date
     *
     * @return string
     */
    public function getBirthday(string $date): string
    {
        $born     = Carbon::parse($date);
        $birthday = Carbon::createFromFormat('md', $born->rawFormat('md'));

        return $birthday->format('Y-m-d');
    }

    /**
     * @param Carbon $date
     *
     * @return bool
     */
    public function isHoliday(Carbon $date): bool
    {
        return in_array($date->format('m-d'), $this->getHolidays(), true);
    }

    /**
     * @param string $dateStr
     *
     * @return string
     */
    public function getNextWorkingDay(string $dateStr): string
    {
        $date = Carbon::parse($dateStr);

        if ($date->isWeekend()) {
            $date = $date->addWeek()->startOfWeek();
            $date->addDay();
        } else {
            $date->addDay();
            if ($date->isWeekend()) {
                $date = $date->addWeek()->startOfWeek();
            }
        }

        if ($this->isHoliday($date)) {
            $date = $this->addDayAfterHoliday($date);
        }

        return $date->format('Y-m-d');
    }

    /**
     * @return array
     */
    public function getHolidays(): array
    {
        return $this->holidays;
    }

    /**
     * @param array $holidays
     */
    public function setHolidays(array $holidays): void
    {
        $this->holidays = $holidays;
    }

    /**
     * @param Carbon $date
     *
     * @return Carbon
     */
    private function addDayAfterHoliday(Carbon $date): Carbon
    {
        $i = 0;
        while ($i < 7) {
            $date->addDay();
            if (!$this->isHoliday($date)) {
                if ($date->isWeekend()) {
                    $date = $date->addWeek()->startOfWeek();
                }
                break;
            }
            $i++;
        }
        return $date;
    }
}
