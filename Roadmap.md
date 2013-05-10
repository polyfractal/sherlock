Roadmap
=======

Sherlock development has begun to run into growing pains as more advanced features are added.  What originally started as a hobby project is quickly being used by multiple people and companies.  It is important that feature additions are balanced by robust internal code organization, proper documentation and a full test suite.

This is a rough roadmap that Sherlock will follow.  No deadlines, but a general overview of what is coming down the pipeline.  If you have questions or comments, please send a message to the mailing list.

 - Transport refactoring
    - The transport section of code is a complete mess.  The current request/response model works fine for single requests, but batch commands makes the paradigm messy.  This is reflected in an external API that is unintuitive and quickly devolves into naming random classes - something I want to avoid in Sherlock
    - The refactoring will rip out much of this internal code and place it under a coherent queuing system so that one or more commands can be specified in a fluent manner.
    - The API will transition from a "index a document" namespace to a generic "document" namespace.  A document can be indexed, deleted or Get using the same syntax.
 - Documentation
    - The current documentation is not sufficient. Work is needed to flesh out the entire usage guide.  This should be done, however, after the transport refactoring since that will significantly change the indexing API
 - Unit and Integration Test Suite
    - The current tests are basically poor integration tests that simply check for exceptions/errors.  The test suite needs to become much more robust to include real unit tests, and more functional integration tests that check response values for accuracy.
 - Test Server
    - Sherlock needs a dedicated test server to run CI, code analysis and performance benchmarks/regression testing.  I have most of this infrastructure set up already, it just needs to be tweaked and put into usage.
 - Consistent Code Style
    - Sherlock needs some love in the code-style department.  Nothing serious, just a tightening of conventions and formatting style across the whole project.
 - Retire magic methods
    - The heavy reliance on magic methods was a boon at the start of Sherlock, since it allowed me to add the majority of the Elasticsearch query DSL quickly and easily.  However, in some internal performance profiling, it is clear that magic methods are upsettingly slow compared to native properties. I want to refactor the majority of Sherlock off magic methods.
    - Luckily, this will not affect the external, developer-facing API.  This refactoring can be performed in the background without breaking the API or requiring development to halt in other locations.