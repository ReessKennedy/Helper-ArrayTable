## What ⚡
A quick, simple, reliable function to convert PHP arrays to text based tables for easy reading at the command line, and beyond. 
## What ⚡
Creating a PHP function that converts array-based data in the terminal into table-based data. 

## Why 🤷‍♂️
Traditional data viewing outputs in PHP like `var_dump` and `print_r` can be hard to read and I just like table formats and I like viewing tables in the terminal. 

## Why 🤷
- Table based data is way easier to read than running `var_dummp($data)` or `print_r($data)` all the time and isn't it way nice to just run `toTable($data)` and have a bunch of options for how the table is displayed!!!!

## How 📋
Like this ... 
```php
    $columns = [
        'Title' => 'title',
        'Created' => 'createdAt',
    ];
    echo toTable($response['data'], $columns);
```


## Complicated Data Issue
### The Challenge
Multiple dimensions often to associative array creates confusion in output
### Possible Solution
Perhaps the function can take two args, one with the original data and one just containing an associate array that contains the column names as keys and the data for the fields as the location for the proper data in the array being passed to the function. For instance perhaps you want to only include `$data['title']` and ` $data['meta']['photos']['image1']['url'] `
## Additional ideas

| Status | Feature                                          | Description                                                                                                                                                                                            | Benefit                                                 | Type       |
| ------ | ------------------------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------- | ---------- |
|        | ApiFile                                          | If you make it into one file you could really advertise it in a cool way ... build it all on a branch and then deploy the final to MAIN branch                                                         | Easy for people                                         |            |
|        | OneFile                                          | Make this into just ONE php file you can drop in anywhere like Adminer ... none of this composer nonsense ...                                                                                          | Easy for people                                         |            |
| ✅      | QueryStats                                       | Show you total rows and where you are ... page and number of pages                                                                                                                                     | Easy to remember where you are                          | CLI + html |
| ✅      | Count Column                                     | Show row count column                                                                                                                                                                                  |                                                         | CLI + html |
| ✅      | TimeAgo                                          | Show time values in time ago ... <br>17 days ago<br>45 minutes ago                                                                                                                                     | Easier to understand time values this way               | CLI + html |
| ✅      | CLI Pagination                                   | PASSING TO Var - Should always pass results to variable first instead of directly injecting via `$resonse['data']['data']` ... storing stuff in data var helps. <br>                                   | Keep reading large data sets in the terminal            | CLI        |
| ✅      | Configurable Widths in Pipes                     | Allow custom widths for each column through an additional parameter.                                                                                                                                   | Enhances visual alignment and customization.            | CLI        |
| 🤔     | Widths via array in value                        | Got PIPES method working ... probably more resource intensive because it has to read each header ... but ... also maybe headers are a nice place to set widths                                         | Alternate column width idea                             | CLI        |
|        | Data Type Handling                               | Improve handling of various data types (dates, numbers) with formatting options.<br><br>RK: Yes - should make sure can handle JSON, CSV or PHP arrays of different formats                             | Ensures correct display and interpretation of data.     | CLI + html |
| 🤔     | toAny                                            | Maybe this should be named toAny instead of toTable and could support data to anything?                                                                                                                |                                                         | IDEA       |
| 🤔     | RK: Search highlighting                          | Related to above ... would be cool.                                                                                                                                                                    |                                                         |            |
| ⏺️     | Flexbot Tables                                   | Flexbot HTML tables<br><br>And maybe options for styles or colors? <br>==Don't do colors or styles ... allow people to just add their CSS but do Flexbot just because this is foundational==           | Work hard to make the HTML table output really great    |            |
| ⏺️     | URL pagination                                   | Just passing same vars to URL and using the override method for this as well so will override anything already defined                                                                                 |                                                         |            |
|        | RK Idea - <br>HTML Templates                     | Maybe you want a cell value to have an HTML template for complex formatting?                                                                                                                           |                                                         |            |
| 🤔     | Styling Options                                  | Introduce parameters for styling headers (e.g., bold, underlined) using special characters or ANSI codes.<br><br>Note: ==Might be tough with command line==                                            | Improves readability and highlights important sections. |            |
| 🤷     | Sorting Options                                  | Add functionality to sort the data based on a specified column and order.<br><br>Note: ==I think this should probably be handled by the API ... only time when note might be with CSV data?==          | Facilitates better data analysis and presentation.      |            |
| 🤷     | Filtering Capabilities                           | Allow passing a filter function or criteria to exclude rows from the final output.<br><br>Note: ==I think this should probably be handled by the API ... only time when note might be with CSV data?== | Enhances control over which data is displayed.          |            |
| 💡     | Column selection via URL or via form post method | Column selections via URL with cookie memory                                                                                                                                                           |                                                         |            |
|        | Cookies                                          | Store certain settings like colummns in cookies so can remember without a database                                                                                                                     | Nice if you dont log in anywhere and wish to rememebr   |            |
|        | Javascript selection                             | Maybe nice JS selection on HTMl version                                                                                                                                                                |                                                         |            |
|        | Ajax / Async selection                           | Maybe use AJAX for selection as well                                                                                                                                                                   |                                                         |            |
## Changelog
2024-July
- ERROR Handling -if column not defined properly then will just return blank col instead of returning full error. 
First pass
- Removed the URL connection that would connect the base API url to method ... not sure ... maybe return this
## Research
- What are some other tools like this? Comps? 
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
