<?php

if($EmpPayRule === 'Fixed Salary'){
    if($Frequency === 'Monthly'){
        $allowance = $TotalAllowanceStandard + $TotaladdAllowance;
        $totalAllowance = $allowance - $DeductionAllowance;
        $allowances = number_format($totalAllowance, 2);

        $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
        $Governmentformat = number_format($Governmenttotal, 2);

        $TranspoAllowance = $Transport - $TransportDeduction;
        $MealAllowance = $Meal - $MealDeduction;
        $InternetAllowance = $Internet - $InternetDeduction;
        $addTotalAllowance = isset($addallowRow['allowance_amount']) && !empty($addallowRow['allowance_amount']) ? ($addallowRow['allowance_amount'] - $TotaladdAllowanceDeduction) : 0;

        $SssAmount = $Sss;
        $TinAmount = $Tin;
        $PagIbigAmount = $PagIbig;
        $PhilhealthAmount = $Philhealth;
        $addTotalGovern = isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern']) ? $addallowRow['TotaladdGovern'] : 0; 

        

    }else if($Frequency === 'Semi-Month'){
        $allowance = $TotalAllowanceStandard + $TotaladdAllowance;
        $totalAllowance = $allowance / 2 - $DeductionAllowance;
        $allowances = number_format($totalAllowance, 2);

        $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
        $totalGovernment = $Governmenttotal / 2;
        $Governmentformat = number_format($totalGovernment, 2);

        $FirstCutoff = '1';
        $LastCutoff = '2';

        $TranspoAllowance = $Transport / 2 - $TransportDeduction;
        $MealAllowance = $Meal / 2 - $MealDeduction;
        $InternetAllowance = $Internet / 2 - $InternetDeduction;
        $addTotalAllowance = isset($addallowRow['allowance_amount']) && !empty($addallowRow['allowance_amount']) ? ($addallowRow['allowance_amount'] / 2 - $TotaladdAllowanceDeduction) : 0;

        $SssAmount = $Sss / 2;
        $TinAmount = $Tin / 2;
        $PagIbigAmount = $PagIbig / 2;
        $PhilhealthAmount = $Philhealth / 2;
        $addTotalGovern = isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern']) ? ($addallowRow['TotaladdGovern'] / 2) : 0;

    }else if($Frequency === 'Weekly'){
        $allowance = $TotalAllowanceStandard + $TotaladdAllowance;
        $totalAllowance = $allowance / 4 - $DeductionAllowance;
        $allowances = number_format($totalAllowance, 2);
        
        $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
        $totalGovernment = $Governmenttotal / 4;
        $Governmentformat = number_format($totalGovernment, 2);

        $FirstCutoff = '1';
        $LastCutoff = '4';

        $TranspoAllowance = $Transport / 4 - $TransportDeduction;
        $MealAllowance = $Meal / 4 - $MealDeduction;
        $InternetAllowance = $Internet / 4 - $InternetDeduction;
        $addTotalAllowance = isset($addallowRow['allowance_amount']) && !empty($addallowRow['allowance_amount']) ? ($addallowRow['allowance_amount'] / 4 - $TotaladdAllowanceDeduction) : 0;

        $SssAmount = $Sss / 4;
        $TinAmount = $Tin / 4;
        $PagIbigAmount = $PagIbig / 4;
        $PhilhealthAmount = $Philhealth / 4;
        $addTotalGovern = (isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern'])) ? ($addallowRow['TotaladdGovern'] / 4) : 0;

    }
        
}else if($EmpPayRule === 'Daily Paid'){
            $DailycutAllowance  = $DailyrateAllowance;//Daily rate ng kabuuang allowance
            $totalAllowance = $Totaldailyworks * $DailycutAllowance; //multiply ang nadivide na allowance sa kung ilang araw ang pinasok sa loob ng cut off
            $allowances = number_format($totalAllowance, 2);
            
            $TranspoAllowance = $Transport - $TransportDeduction;
            $MealAllowance = $Meal - $MealDeduction;
            $InternetAllowance = $Internet - $InternetDeduction;
            $addTotalAllowance = 0; // Initialize to 0 by default

            if (isset($addallowRow['allowance_amount']) && $addallowRow['allowance_amount'] != 0) {
                $addTotalAllowance = $addallowRow['allowance_amount'] - $TotaladdAllowanceDeduction;
            }

            if($Frequency === 'Monthly'){        
                $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
                $Governmentformat = number_format($Governmenttotal, 2);

                $SssAmount = $Sss;
                $TinAmount = $Tin;
                $PagIbigAmount = $PagIbig;
                $PhilhealthAmount = $Philhealth;
                $addTotalGovern = isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern']) ? $addallowRow['TotaladdGovern'] : 0; 

            }else if($Frequency === 'Semi-Month'){        
                $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
                $totalGovernment = $Governmenttotal / 2;
                $Governmentformat = number_format($totalGovernment, 2);

                $SssAmount = $Sss / 2;
                $TinAmount = $Tin / 2;
                $PagIbigAmount = $PagIbig / 2;
                $PhilhealthAmount = $Philhealth / 2;
                $addTotalGovern = isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern']) ? ($addallowRow['TotaladdGovern'] / 2) : 0;

            }else if($Frequency === 'Weekly'){                                                                        
                $Governmenttotal = $TotalGovernStandard + $TotaladdGovern;
                $totalGovernment = $Governmenttotal / 4;
                $Governmentformat = number_format($totalGovernment, 2);

                $SssAmount = $Sss / 4;
                $TinAmount = $Tin / 4;
                $PagIbigAmount = $PagIbig / 4;
                $PhilhealthAmount = $Philhealth / 4;
                $addTotalGovern = (isset($addallowRow['TotaladdGovern']) && !empty($addallowRow['TotaladdGovern'])) ? ($addallowRow['TotaladdGovern'] / 4) : 0;

            }   
        }
          
?>
