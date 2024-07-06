## What ⚡
A quick, simple, reliable function to convert PHP arrays to text based tables for easy reading at the command line, and beyond. 
## Why 🤷‍♂️
Traditional data viewing outputs in PHP like `var_dump` and `print_r` can be hard to read and I just like table formats and I like viewing tables in the terminal. 
## How 📋
Like this ... 
```php
    $columns = [
        'Title' => 'title',
        'Created' => 'createdAt',

    ];

    echo arrayToTable($response['data']['data'], $columns);
```
## Resources 🌐

### Ideas 💡
- Could expand to add pagination
- Quick conversion to other formats
	- Yes, do these:
		- HTML
		- CSV
	- Not worth it given likely extra lib req and actual value add: 
		- IMAGE?
		- Excel?
		- Pdf? 
