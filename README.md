# CaughtCam
Caught on Camera iOS app, using PHP to scrape the current list from the Drupal website of Nottingham Police.

## Current state
Currently, this app got rejected from the App Store, and is currently in development (read: abandoned until further notice).

This repo serves as a home for the code for the PHP scraper, aswell as the App.

This is currently what the output from the scraper looks like:

    {
        "link": "\/caught-on-camera\/ordsall-shop-theft",
        "image": "http:\/\/www.nottinghamshire.police.uk\/sites\/default\/files\/styles\/police_appeals_thumbnail\/public\/caught%20on%20camera%2C%20Kings%20Stores%2C%20Ordsall.jpg?itok=2y4Nte9X",
        "title": "Ordsall: Shop Theft",
        "text": "Do you know this man?&nbsp;",
        "date": "Not yet collected"
    }

There are issues:

* App not included
* Date not included
* More information

## Next steps
I hope to submit this to the AppStore later this year with working code, with the idea of adding future police forces into the system.
