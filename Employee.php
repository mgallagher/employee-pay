<?php 

class Employee
{

	private $name;
	private $wage;
	private $dependents;

	const FULL_TIME = 40;
	const SOCIAL_SECURITY = 6.2;
	const MEDICARE = 1.45;
	const MINIMUM_WAGE = 7.25;
	const DEPENDENT_WITHHOLDING = 75;

	public function __construct()
	{
		$num = func_num_args();

		if ($num == 1)
		{
			$this->setName(func_get_arg(0));
		}

		else if ($num == 2)
		{
			$this->setName(func_get_arg(0));
			$this->setWage(func_get_arg(1));
		}

		else if ($num == 3)
		{
			$this->setName(func_get_arg(0));
			$this->setWage(func_get_arg(1));
			$this->setDependents(func_get_arg(2));
		}


	}

	public function setName($input)
	{
		$this->name = $input;
	}

	public function setWage($input)
	{
		if ($input < self::MINIMUM_WAGE)
		{
			$this->wage = self::MINIMUM_WAGE;
		}

		else
		{
			$this->wage = $input;
		}
	}

	public function setDependents($input)
	{
		if ($input < 0)
		{
			$this->dependents = 0;
		}

		else if ($input > 9)
		{
			$this->dependents = 9;
		}

		else
		{
			$this->dependents = $input;
		}
	}

	public function __toString()
	{
		return "$this->name";
	}

	public function computePay($hours)
	{
		$grossPay = 0;
		$netPay = 0;

		if ($hours < 0)
		{
			$hours = 0;
			return $netPay;
		}

		else
		{
			// Overtime
			if ($hours > self::FULL_TIME)
			{
				$overtimeHours = $hours - self::FULL_TIME;
				$overtimePay = ($overtimeHours * $this->wage * 1.5);
				$grossPay = (self::FULL_TIME * $this->wage) + $overtimePay;
			}

			// Full-time and under
			else
			{
				$grossPay = $hours * $this->wage;
			}
		}

		// Social security withdrawal
		$socialWithdrawal = $grossPay * (self::SOCIAL_SECURITY * .01);

		// Medicare withdrawal
		$mediWithdrawal = $grossPay * (self::MEDICARE * .01);

		// Tax withholdings
		$allowance = $this->dependents * self::DEPENDENT_WITHHOLDING;
		$afterAllowance = $grossPay - $allowance;
		$taxWithheld = 0;

		// Stores the values of the tax chart as arrays
		// I should have just done this in ONE 3d array instead, would have used
		// a lot less code that way and could have calculated in a for-loop
		$taxChart = array(160, 503, 1554, 2975, 4449, 7820, 8813);
		$incomeWithhold = array
			(
				   // 0		     1
				array(0,		.1),		// 0 
				array(34.30,	.15),		// 1
				array(191.95,	.25),		// 2
				array(547.20,	.28),		// 3
				array(959.92,	.33),		// 4
				array(2072.35,	.35),		// 5
				array(2419.90,	.396),		// 6
			);

		$taxChartArray = array
			(
				   // 0		    1            2
				array(160,		0,			.1),		// 0 
				array(503,		34.30,		.15),		// 1
				array(1554,		191.95,		.25),		// 2
				array(2975,		547.20,		.28),		// 3
				array(4449,		959.92,		.33),		// 4
				array(7820,		2072.35,	.35),		// 5
				array(8813,		2419.90,	.396),		// 6
			);


		// Anything under $160
		if ($afterAllowance < $taxChart[0])
		{
			$taxWithheld = 0;
		}

		// $160 to $503
		else if ($afterAllowance > $taxChart[0] && $afterAllowance < $taxChart[1])
		{
			$taxWithheld = (($afterAllowance - $taxChart[0]) * $incomeWithhold[0][1] 
				+ $incomeWithhold[0][0]);
		}

		// $503 to $1554
		else if ($afterAllowance > $taxChart[1] && $afterAllowance < $taxChart[2])
		{
			$taxWithheld = (($afterAllowance - $taxChart[1]) * $incomeWithhold[1][1] 
				+ $incomeWithhold[1][0]);
		}

		// $1554 to $2975
		else if ($afterAllowance > $taxChart[2] && $afterAllowance < $taxChart[3])
		{
			$taxWithheld = (($afterAllowance - $taxChart[2]) * $incomeWithhold[2][1] 
				+ $incomeWithhold[2][0]);
		}

		// $2975 to $4449
		else if ($afterAllowance > $taxChart[3] && $afterAllowance < $taxChart[4])
		{
			$taxWithheld = (($afterAllowance - $taxChart[3]) * $incomeWithhold[3][1] 
				+ $incomeWithhold[3][0]);
		}

		// $4449 to $7820
		else if ($afterAllowance > $taxChart[4] && $afterAllowance < $taxChart[5])
		{
			$taxWithheld = (($afterAllowance - $taxChart[4]) * $incomeWithhold[4][1] 
				+ $incomeWithhold[4][0]);
		}

		// $7820 to $8813
		else if ($afterAllowance > $taxChart[5] && $afterAllowance < $taxChart[6])
		{
			$taxWithheld = (($afterAllowance - $taxChart[5]) * $incomeWithhold[5][1] 
				+ $incomeWithhold[5][0]);
		}

		// Anything ABOVE $8813
		else if ($afterAllowance > $taxChart[6])
		{
			$taxWithheld = (($afterAllowance - $taxChart[6]) * $incomeWithhold[6][1] 
				+ $incomeWithhold[6][0]);
		}

		$netPay = $grossPay - $socialWithdrawal - $mediWithdrawal - $taxWithheld;

		return number_format($netPay, 2);
	}
}

 ?>
