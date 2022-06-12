# TubeCrush

## Tests

### Cypress

To run Cypress tests on Mac you will need to install xQuartz first. Then, open xQuartz, go to Preferences -> Security
and tick the "Allow connections from network clients" box. This is a one-off and you will not need to do it again.

Now you are ready to open Cypress. In a terminal, run the following commands

- `cd <your project root>`
- `DISPLAY=:0 /usr/X11/bin/xhost +`
- `sail run -it --rm cypress open --project .`

You can pass a few options to the last command. For example, if you want to run only e2e test you can use
the `--e2e` option. Or if you want to run the test on a specific browser, for example Electron, you can
use `--browser electron`.
