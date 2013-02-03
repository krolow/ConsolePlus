ConsolePlus
===========

Additional commands and helpers for CakePHP 2 Console.

## What this Plugin provides:

* [ConsoleTable](https://github.com/krolow/ConsolePlus/blob/master/Console/ConsoleTable.php) - A helper to output table data in console. (Not finished yet)
* ConsoleTree - A helper to output tree data in console; (Not implemented yet)
* RouterCommand - List of all routes defined in your application;

## Usage

```bash
php app/Console/cake.php ConsolePlus.Router

+---------------------------------------------------------------------------------------------------------------------------------+
|                                                         List of Routes                                                          |
+---------------------------------------------------------------------------------------------------------------------------------+
| Controller::action                                | Method                      | Route                                         |
+---------------------------------------------------------------------------------------------------------------------------------+
| ContentsController::view($content)                | ANY                         | /contents/view/{content}/                     |
| ContentsController::detail($content, $test, $lol) | ANY                         | /contents/detail/{content}/{test}/{lol}/      |
| ContentsController::edit($id)                     | GET|PUT|DELETE|HEAD|OPTIONS | /contents/edit/{id}/                          |
| ContentsController::edit($id)                     | POST                        | /edit/{id}                                    |
| PagesController::display()                        | ANY                         | /pages                                        |
| ToolbarAccessController::history_state($key)      | ANY                         | /debug_kit/toolbar_access/history_state/{key} |
| ToolbarAccessController::sql_explain()            | ANY                         | /debug_kit/toolbar_access/sql_explain         |
+---------------------------------------------------------------------------------------------------------------------------------+
```

## License

Licensed under <a href="http://www.opensource.org/licenses/mit-license.php">The MIT License</a>
Redistributions of files must retain the above copyright notice.

## Author

Vinícius Krolow - krolow[at]gmail.com
