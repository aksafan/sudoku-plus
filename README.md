# sudoku-plus

## What is Sudoku Plus?
Sudoku Plus is a game which follows all the same rules of Sudoku but has
a grid of variable size. A Sudoku Plus board might be 4x4, 9x9, 16x16,
100x100 etc. All valid grids will have the following characteristics:

- The grid will be square (same number of rows and columns).
- The length of a side should have an integer value square root. Valid
side lengths would include: 4, 9,16, etc.
- The grid can be divided into square regions of equal size where the
size of a region is equal to the square root of a side of the entire grid.
Each region will have the same number of cells as rows in the grid.
On a 16x16 grid there would be 16 regions of size 4x4.
- The numbers that can be used are in the range from 1 to N where N
is the length of a side.

## How to run

1. Run `php index.php` in order to start script and check several predefined
Sudoku plus boards (4x4, 9x9, 16x16).
2. Run `php index.php {1:2:3:4,3:4:1:2,2:3:4:1,4:1:2:3}` in order to start script and check your own board.
`{1:2:3:4,3:4:1:2,2:3:4:1,4:1:2:3}` is equivalent to 
```php
    [
        [1, 2, 3, 4],
        [3, 4, 1, 2],
        [2, 3, 4, 1],
        [4, 1, 2, 3],
    ]
```