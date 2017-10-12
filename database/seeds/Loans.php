<?php

use Illuminate\Database\Seeder;

class Loans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        stdClass Object
    (
        [id] => 74
    [accountNo] => SAL000000074
    [status] => stdClass Object
    (
        [id] => 100
            [code] => loanStatusType.submitted.and.pending.approval
            [value] => Submitted and pending approval
            [pendingApproval] => 1
            [waitingForDisbursal] =>
            [active] =>
            [closedObligationsMet] =>
            [closedWrittenOff] =>
            [closedRescheduled] =>
            [closed] =>
            [overpaid] =>
        )

    [clientId] => 46
    [clientAccountNo] => 000000046
    [clientName] => Leonard Korir
    [clientOfficeId] => 1
    [loanProductId] => 1
    [loanProductName] => Salary Advance Loan
    [loanProductDescription] => Salary advance loan
    [isLoanProductLinkedToFloatingRate] =>
    [loanType] => stdClass Object
    (
        [id] => 1
            [code] => accountType.individual
            [value] => Individual
        )

    [currency] => stdClass Object
    (
        [code] => KES
            [name] => Kenyan Shilling
            [decimalPlaces] => 0
            [inMultiplesOf] => 1
            [displaySymbol] => KSh
            [nameCode] => currency.KES
            [displayLabel] => Kenyan Shilling (KSh)
        )

    [principal] => 10000
    [approvedPrincipal] => 10000
    [proposedPrincipal] => 10000
    [termFrequency] => 2
    [termPeriodFrequencyType] => stdClass Object
    (
        [id] => 2
            [code] => termFrequency.periodFrequencyType.months
            [value] => Months
        )

    [numberOfRepayments] => 2
    [repaymentEvery] => 1
    [repaymentFrequencyType] => stdClass Object
    (
        [id] => 2
            [code] => repaymentFrequency.periodFrequencyType.months
            [value] => Months
        )

    [interestRatePerPeriod] => 7.5
    [interestRateFrequencyType] => stdClass Object
    (
        [id] => 2
            [code] => interestRateFrequency.periodFrequencyType.months
            [value] => Per month
        )

    [annualInterestRate] => 90
    [isFloatingInterestRate] =>
    [amortizationType] => stdClass Object
    (
        [id] => 1
            [code] => amortizationType.equal.installments
            [value] => Equal installments
        )

    [interestType] => stdClass Object
    (
        [id] => 1
            [code] => interestType.flat
            [value] => Flat
        )

    [interestCalculationPeriodType] => stdClass Object
    (
        [id] => 1
            [code] => interestCalculationPeriodType.same.as.repayment.period
            [value] => Same as repayment period
        )

    [allowPartialPeriodInterestCalcualtion] =>
    [transactionProcessingStrategyId] => 1
    [transactionProcessingStrategyName] => Penalties, Fees, Interest, Principal order
    [syncDisbursementWithMeeting] =>
    [timeline] => stdClass Object
    (
        [submittedOnDate] => Array
    (
        [0] => 2017
                    [1] => 10
                    [2] => 12
                )

            [submittedByUsername] => mifos
            [submittedByFirstname] => App
            [submittedByLastname] => Administrator
            [expectedDisbursementDate] => Array
    (
        [0] => 2017
                    [1] => 10
                    [2] => 12
                )

            [expectedMaturityDate] => Array
    (
        [0] => 2017
                    [1] => 12
                    [2] => 12
                )

        )

    [repaymentSchedule] => stdClass Object
    (
        [currency] => stdClass Object
    (
        [code] => KES
                    [name] => Kenyan Shilling
                    [decimalPlaces] => 0
                    [inMultiplesOf] => 1
                    [displaySymbol] => KSh
                    [nameCode] => currency.KES
                    [displayLabel] => Kenyan Shilling (KSh)
                )

            [loanTermInDays] => 61
            [totalPrincipalDisbursed] => 10000
            [totalPrincipalExpected] => 10000
            [totalPrincipalPaid] => 0
            [totalInterestCharged] => 1139
            [totalFeeChargesCharged] => 0
            [totalPenaltyChargesCharged] => 0
            [totalWaived] => 0
            [totalWrittenOff] => 0
            [totalRepaymentExpected] => 11139
            [totalRepayment] => 0
            [totalPaidInAdvance] => 0
            [totalPaidLate] => 0
            [totalOutstanding] => 11139
            [periods] => Array
    (
        [0] => stdClass Object
    (
        [dueDate] => Array
    (
        [0] => 2017
                                    [1] => 10
                                    [2] => 12
                                )

                            [principalDisbursed] => 10000
                            [principalLoanBalanceOutstanding] => 10000
                            [feeChargesDue] => 0
                            [feeChargesOutstanding] => 0
                            [totalOriginalDueForPeriod] => 0
                            [totalDueForPeriod] => 0
                            [totalOutstandingForPeriod] => 0
                            [totalActualCostOfLoanForPeriod] => 0
                        )

                    [1] => stdClass Object
    (
        [period] => 1
                            [fromDate] => Array
    (
        [0] => 2017
                                    [1] => 10
                                    [2] => 12
                                )

                            [dueDate] => Array
    (
        [0] => 2017
                                    [1] => 11
                                    [2] => 13
                                )

                            [complete] =>
                            [daysInPeriod] => 32
                            [principalOriginalDue] => 4819
                            [principalDue] => 4819
                            [principalPaid] => 0
                            [principalWrittenOff] => 0
                            [principalOutstanding] => 4819
                            [principalLoanBalanceOutstanding] => 5181
                            [interestOriginalDue] => 750
                            [interestDue] => 750
                            [interestPaid] => 0
                            [interestWaived] => 0
                            [interestWrittenOff] => 0
                            [interestOutstanding] => 750
                            [feeChargesDue] => 0
                            [feeChargesPaid] => 0
                            [feeChargesWaived] => 0
                            [feeChargesWrittenOff] => 0
                            [feeChargesOutstanding] => 0
                            [penaltyChargesDue] => 0
                            [penaltyChargesPaid] => 0
                            [penaltyChargesWaived] => 0
                            [penaltyChargesWrittenOff] => 0
                            [penaltyChargesOutstanding] => 0
                            [totalOriginalDueForPeriod] => 5569
                            [totalDueForPeriod] => 5569
                            [totalPaidForPeriod] => 0
                            [totalPaidInAdvanceForPeriod] => 0
                            [totalPaidLateForPeriod] => 0
                            [totalWaivedForPeriod] => 0
                            [totalWrittenOffForPeriod] => 0
                            [totalOutstandingForPeriod] => 5569
                            [totalActualCostOfLoanForPeriod] => 750
                            [totalInstallmentAmountForPeriod] => 5569
                        )

                    [2] => stdClass Object
    (
        [period] => 2
                            [fromDate] => Array
    (
        [0] => 2017
                                    [1] => 11
                                    [2] => 13
                                )

                            [dueDate] => Array
    (
        [0] => 2017
                                    [1] => 12
                                    [2] => 12
                                )

                            [complete] =>
                            [daysInPeriod] => 29
                            [principalOriginalDue] => 5181
                            [principalDue] => 5181
                            [principalPaid] => 0
                            [principalWrittenOff] => 0
                            [principalOutstanding] => 5181
                            [principalLoanBalanceOutstanding] => 0
                            [interestOriginalDue] => 389
                            [interestDue] => 389
                            [interestPaid] => 0
                            [interestWaived] => 0
                            [interestWrittenOff] => 0
                            [interestOutstanding] => 389
                            [feeChargesDue] => 0
                            [feeChargesPaid] => 0
                            [feeChargesWaived] => 0
                            [feeChargesWrittenOff] => 0
                            [feeChargesOutstanding] => 0
                            [penaltyChargesDue] => 0
                            [penaltyChargesPaid] => 0
                            [penaltyChargesWaived] => 0
                            [penaltyChargesWrittenOff] => 0
                            [penaltyChargesOutstanding] => 0
                            [totalOriginalDueForPeriod] => 5570
                            [totalDueForPeriod] => 5570
                            [totalPaidForPeriod] => 0
                            [totalPaidInAdvanceForPeriod] => 0
                            [totalPaidLateForPeriod] => 0
                            [totalWaivedForPeriod] => 0
                            [totalWrittenOffForPeriod] => 0
                            [totalOutstandingForPeriod] => 5570
                            [totalActualCostOfLoanForPeriod] => 389
                            [totalInstallmentAmountForPeriod] => 5570
                        )

                )

        )

    [disbursementDetails] => Array
    (
    )

    [feeChargesAtDisbursementCharged] => 0
    [multiDisburseLoan] =>
    [canDefineInstallmentAmount] =>
    [canDisburse] =>
    [emiAmountVariations] => Array
    (
    )

    [canUseForTopup] =>
    [isTopup] =>
    [closureLoanId] => 0
    [inArrears] =>
    [isNPA] =>
    [overdueCharges] => Array
    (
        [0] => stdClass Object
    (
        [id] => 1
                    [name] => Late Payment Penalty
                    [active] => 1
                    [penalty] => 1
                    [currency] => stdClass Object
    (
        [code] => KES
                            [name] => Kenyan Shilling
                            [decimalPlaces] => 2
                            [displaySymbol] => KSh
                            [nameCode] => currency.KES
                            [displayLabel] => Kenyan Shilling (KSh)
                        )

                    [amount] => 1
                    [chargeTimeType] => stdClass Object
    (
        [id] => 9
                            [code] => chargeTimeType.overdueInstallment
                            [value] => Overdue Fees
                        )

                    [chargeAppliesTo] => stdClass Object
    (
        [id] => 1
                            [code] => chargeAppliesTo.loan
                            [value] => Loan
                        )

                    [chargeCalculationType] => stdClass Object
    (
        [id] => 3
                            [code] => chargeCalculationType.percent.of.amount.and.interest
                            [value] => % Loan Amount + Interest
                        )

                    [chargePaymentMode] => stdClass Object
    (
        [id] => 0
                            [code] => chargepaymentmode.regular
                            [value] => Regular
                        )

                    [feeInterval] => 1
                    [feeFrequency] => stdClass Object
    (
        [id] => 0
                            [code] => feeFrequencyperiodFrequencyType.days
                            [value] => Days
                        )

                )

        )

    [daysInMonthType] => stdClass Object
    (
        [id] => 1
            [code] => DaysInMonthType.actual
            [value] => Actual
        )

    [daysInYearType] => stdClass Object
    (
        [id] => 1
            [code] => DaysInYearType.actual
            [value] => Actual
        )

    [isInterestRecalculationEnabled] =>
    [createStandingInstructionAtDisbursement] =>
    [paidInAdvance] => stdClass Object
    (
        [paidInAdvance] => 0
        )

    [isVariableInstallmentsAllowed] =>
    [minimumGap] => 0
    [maximumGap] => 0
)



    }
}
