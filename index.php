<?php

final readonly class SudokuPlus
{
    public function __construct(
        /** @var int[][] $board */
        private array $board
    ) {
    }

    /**
     * Time complexity O(n^2)
     *
     * @return bool
     */
    public function isValidSudokuPlus(): bool
    {
        $size = count($this->board);

        // The 1st constraint - checking if the grid is square
        if (!$this->isSquare($size)) {
            return false;
        }

        // The 2nd constraint - checking if the side length has an integer square root. E.g. 4, 9, 16, etc.
        $sizeSquareRoot = $this->getSquareRootByNumber($size);
        if (!$this->isIntegerSquareRoot($size, $sizeSquareRoot)) {
            return false;
        }

        // The 4th constraint - checking rows and columns if the numbers that can be used are in the range from 1 to N
        // where N is the length of a side.
        if (!$this->isValidRowsAndColumns($size)) {
            return false;
        }

        // The 3rd constraint - checking every square region if the numbers that can be used are in the range from 1 to N
        // where N is the length of a side.
         if (!$this->isValidRegion($size, $sizeSquareRoot)) {
             return false;
         }

        return true;
    }

    /**
     * Heron's method for approximating the square root of a number.
     * Runs in O(log(n))
     *
     * @param int $n
     *
     * @return int
     */
    private function getSquareRootByNumber(int $n): int
    {
        $x = $n;
        $y = ($n + 1) / 2;

        while ($x != $y) {
            $x = $y;
            $y = intval(($x ** 2 + $n) / (2 * $x));
        }

        return $x;
    }

    /**
     * Checks if the numbers that can be used are in the range from 1 to N where N
     * is the length of a side.
     *
     * @param $arrayToCheck int[]
     * @param $size int The length of a side
     *
     * @return bool
     */
    private function isValidRange(array $arrayToCheck, int $size): bool
    {
        $validNumbers = range(1, $size);
        sort($arrayToCheck);

        return $arrayToCheck === $validNumbers;
    }

    /**
     * Checks every square region if the numbers that can be used are in the range from 1 to N
     * where N is the length of a side.
     * Time complexity O(n^2) as 2 outer loops are both `size/sizeSquareRoot` and
     * 2 inner loops are both `sizeSquareRoot` that leads to size^2`
     *
     * @param int $size
     * @param int $sizeSquareRoot
     *
     * @return bool
     */
    private function isValidRegion(int $size, int $sizeSquareRoot): bool
    {
        for ($row = 0; $row < $size; $row += $sizeSquareRoot) {
            for ($col = 0; $col < $size; $col += $sizeSquareRoot) {
                $region = [];
                for ($i = 0; $i < $sizeSquareRoot; $i++) {
                    for ($j = 0; $j < $sizeSquareRoot; $j++) {
                        $rowIndex = $row + $i;
                        $columnIndex = $col + $j;
                        $region[] = $this->board[$rowIndex][$columnIndex];
                    }
                }
                if (!$this->isValidRange($region, $size)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Checks rows and columns if the numbers that can be used are in the range from 1 to N
     * where N is the length of a side.
     * Time complexity O(n)
     *
     * @param int $size
     *
     * @return bool
     */
    private function isValidRowsAndColumns(int $size): bool
    {
        for ($i = 0; $i < $size; $i++) {
            $isValidRow = $this->isValidRange($this->board[$i], $size);
            $isValidColumn = $this->isValidRange(array_column($this->board, $i), $size);
            if (!$isValidRow || !$isValidColumn) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the side length has an integer square root. E.g. 4, 9, 16, etc.
     * Time complexity O(log(n))
     *
     * @param int $size
     * @param int $sizeSquareRoot
     *
     * @return bool
     */
    private function isIntegerSquareRoot(int $size, int $sizeSquareRoot): bool
    {
        return $sizeSquareRoot ** 2 === $size;
    }

    /**
     * Checks if the grid is square
     *
     * @param int $size
     * @return bool
     */
    private function isSquare(int $size): bool
    {
        return !empty($this->board[0]) && count($this->board[0]) === $size;
    }
}

if (count($argv) > 1) {
    $board = [];
    for ($i = 1; $i < count($argv); $i++) {
        $board[] = array_map(fn(string $number): int => (int) $number, explode(':', $argv[$i]));
    }

    $sudokuPlus = new SudokuPlus($board);
    if ($sudokuPlus->isValidSudokuPlus()) {
        echo "The given Sudoku Plus board is valid.\n";
    } else {
        echo "The given Sudoku Plus board is invalid.\n";
    }

    exit(0);
}

$validSudokuPlusBoard4x4 = [
    [1, 2, 3, 4],
    [3, 4, 1, 2],
    [2, 3, 4, 1],
    [4, 1, 2, 3],
];

$sudokuPlus = new SudokuPlus($validSudokuPlusBoard4x4);
$rowsTotal = count($validSudokuPlusBoard4x4);
if ($sudokuPlus->isValidSudokuPlus()) {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is valid.\n";
} else {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is invalid.\n";
}

$invalidSudokuPlusBoard4x4 = [
    [1, 2, 3, 4],
    [3, 4, 1, 2],
    [2, 3, 4, 1],
    [4, 1, 2, 4],  // Here is an invalid value in cell (4:4) - `4`
];
$sudokuPlus = new SudokuPlus($invalidSudokuPlusBoard4x4);
$rowsTotal = count($invalidSudokuPlusBoard4x4);
if ($sudokuPlus->isValidSudokuPlus()) {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is valid.\n";
} else {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is invalid.\n";
}

$validSudokuPlusBoard9x9 = [
    [5, 3, 4, 6, 7, 8, 9, 1, 2],
    [6, 7, 2, 1, 9, 5, 3, 4, 8],
    [1, 9, 8, 3, 4, 2, 5, 6, 7],
    [8, 5, 9, 7, 6, 1, 4, 2, 3],
    [4, 2, 6, 8, 5, 3, 7, 9, 1],
    [7, 1, 3, 9, 2, 4, 8, 5, 6],
    [9, 6, 1, 5, 3, 7, 2, 8, 4],
    [2, 8, 7, 4, 1, 9, 6, 3, 5],
    [3, 4, 5, 2, 8, 6, 1, 7, 9],
];
$sudokuPlus = new SudokuPlus($validSudokuPlusBoard9x9);
$rowsTotal = count($validSudokuPlusBoard9x9);
if ($sudokuPlus->isValidSudokuPlus()) {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is valid.\n";
} else {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is invalid.\n";
}

$validSudokuPlusBoard16x16 = [
    [5, 3, 4, 6, 7, 8, 9, 1, 2, 10, 11, 12, 13, 14, 15, 16],
    [6, 7, 2, 1, 9, 5, 3, 4, 8, 11, 12, 13, 14, 15, 16, 10],
    [1, 9, 8, 3, 4, 2, 5, 6, 7, 12, 13, 14, 15, 16, 10, 11],
    [8, 5, 9, 7, 6, 1, 4, 2, 3, 13, 14, 15, 16, 10, 11, 12],
    [4, 2, 6, 8, 5, 3, 7, 9, 1, 14, 15, 16, 10, 11, 12, 13],
    [7, 1, 3, 9, 2, 4, 8, 5, 6, 15, 16, 10, 11, 12, 13, 14],  // Here is an invalid value in cell (8:6) - `4`
    [9, 6, 1, 5, 3, 7, 2, 8, 4, 16, 10, 11, 12, 13, 14, 15],
    [2, 8, 7, 4, 1, 9, 6, 3, 5, 10, 11, 12, 13, 14, 15, 16],
    [3, 4, 5, 2, 8, 6, 1, 7, 9, 11, 12, 13, 14, 15, 16, 10],
    [10, 11, 12, 13, 14, 15, 16, 3, 4, 5, 6, 7, 8, 9, 1, 2],
    [11, 12, 13, 14, 15, 16, 10, 4, 5, 6, 7, 8, 9, 1, 2, 3],
    [12, 13, 14, 15, 16, 10, 11, 5, 6, 7, 8, 9, 1, 2, 3, 4],
    [13, 14, 15, 16, 10, 11, 12, 6, 7, 8, 9, 1, 2, 3, 4, 5],
    [14, 15, 16, 10, 11, 12, 13, 7, 8, 9, 1, 2, 3, 4, 5, 6],
    [15, 16, 10, 11, 12, 13, 14, 8, 9, 1, 2, 3, 4, 5, 6, 7],
    [16, 10, 11, 12, 13, 14, 15, 9, 1, 2, 3, 4, 5, 6, 7, 8],
];
$sudokuPlus = new SudokuPlus($validSudokuPlusBoard16x16);
$rowsTotal = count($validSudokuPlusBoard16x16);
if ($sudokuPlus->isValidSudokuPlus()) {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is valid.\n";
} else {
    echo "The $rowsTotal x $rowsTotal Sudoku Plus board is invalid.\n";
}
