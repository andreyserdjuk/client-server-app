About
-----
Example client-server application config built with Symfony 3, React, Webpack, TypeScript.

Setup
-----
```bash
bin/console do:da:cre
bin/console do:mi:mi
bin/console do:fi:lo
bin/console server:run
./vendor/bin/phpunit -c phpunit.xml.dist

# setup assets
npm i
npm run webpack

# see request-response log
tail -f var/logs/dev_request.log
```
"/api/doc" - Nelmio api doc.  
"/" - React client interface.

P.S.
----
Due to poor Symfony encore docs, empty "test" sections, 
huge and unclear webpack config etc. encore is not used here.