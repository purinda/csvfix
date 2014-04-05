@extends('layouts.master')

@section('content')
<div class="container">
<div class="section"><h2>What is CSV?<a name="What_is_CSV"></a></h2><p>The comma-separated values (CSV) format is a widely used text file format often used to exchange data between applications. It contains multiple records (one per line), and each field is delimited by a comma. <a class="externalLink" href="http://en.wikipedia.org/wiki/Comma-separated_values">Wikipedia</a> has a good explanation of the CSV format and its history.</p><p>There is no definitive standard for CSV, however the most commonly accepted definition is <a class="externalLink" href="http://tools.ietf.org/html/rfc4180">RFC 4180</a> - the MIME type definition for CSV. Super CSV is 100% compliant with RFC 4180, while still allowing some flexibility where CSV files deviate from the definition.</p><p>The following shows each rule defined in RFC 4180, and how it is treated by Super CSV.</p><div class="section"><h3>Rule 1<a name="Rule_1"></a></h3><div><pre>1. Each record is located on a separate line, delimited by a line
   break (CRLF).  For example:

   aaa,bbb,ccc CRLF
   zzz,yyy,xxx CRLF</pre></div><p>Super CSV accepts all line breaks (Windows, Mac or Unix) when reading CSV files, and uses the end of line symbols specified by the user (via the <a href="./apidocs/org/supercsv/prefs/CsvPreference.html">CsvPreference</a> object) when writing CSV files.</p></div><div class="section"><h3>Rule 2<a name="Rule_2"></a></h3><div><pre>2. The last record in the file may or may not have an ending line
   break.  For example:

   aaa,bbb,ccc CRLF
   zzz,yyy,xxx</pre></div><p>Super CSV <i>will</i> add a line break when writing the last line of a CSV file, but a line break on the last line is optional when reading.</p></div><div class="section"><h3>Rule 3<a name="Rule_3"></a></h3><div><pre>3. There maybe an optional header line appearing as the first line
   of the file with the same format as normal record lines.  This
   header will contain names corresponding to the fields in the file
   and should contain the same number of fields as the records in
   the rest of the file (the presence or absence of the header line
   should be indicated via the optional "header" parameter of this
   MIME type).  For example:

   field_name,field_name,field_name CRLF
   aaa,bbb,ccc CRLF
   zzz,yyy,xxx CRLF</pre></div><p>Super CSV provides methods for reading and writing headers, if required. It also makes use of the header for mapping between CSV and POJOs (see <a href="./apidocs/org/supercsv/io/CsvBeanReader.html">CsvBeanReader</a>/<a href="./apidocs/org/supercsv/io/CsvBeanWriter.html">CsvBeanWriter</a>).</p></div><div class="section"><h3>Rule 4<a name="Rule_4"></a></h3><div><pre>4. Within the header and each record, there may be one or more
   fields, separated by commas.  Each line should contain the same
   number of fields throughout the file.  Spaces are considered part
   of a field and should not be ignored.  The last field in the
   record must not be followed by a comma.  For example:

   aaa,bbb,ccc</pre></div><p>The delimiter in Super CSV is configurable via the <a href="./apidocs/org/supercsv/prefs/CsvPreference.html">CsvPreference</a> object, though it is typically a comma.</p><p>Super CSV expects each line to contain the same number of fields (including the header). In cases where the number of fields varies, <a href="./apidocs/org/supercsv/io/CsvListReader.html">CsvListReader</a>/<a href="./apidocs/org/supercsv/io/CsvListWriter.html">CsvListWriter</a> should be used, as they contain methods for reading/writing lines of arbitrary length.</p><p>By default, Super CSV considers spaces part of a field. However, if you require that surrounding spaces should not be part of the field (unless within double quotes), then you can enable <i>surroundingSpacesNeedQuotes</i> in your <a href="./apidocs/org/supercsv/prefs/CsvPreference.html">CsvPreference</a> object. This will ensure that surrounding spaces are trimmed when reading (if not within double quotes), and that quotes are applied to a field with surrounding spaces when writing. </p></div><div class="section"><h3>Rule 5<a name="Rule_5"></a></h3><div><pre>5. Each field may or may not be enclosed in double quotes (however
   some programs, such as Microsoft Excel, do not use double quotes
   at all).  If fields are not enclosed with double quotes, then
   double quotes may not appear inside the fields.  For example:

   "aaa","bbb","ccc" CRLF
   zzz,yyy,xxx</pre></div><p>By default Super CSV only encloses fields in double quotes when they require escaping (see Rule 6), but it is possible to enable quotes always, for particular columns, or for some other reason by supplying a <a href="./apidocs/org/supercsv/quote/QuoteMode.html">QuoteMode</a> in the CsvPreference object.</p><p>The quote character is configurable via the <a href="./apidocs/org/supercsv/prefs/CsvPreference.html">CsvPreference</a> object, though is typically a double quote (<tt>"</tt>).</p></div><div class="section"><h3>Rule 6<a name="Rule_6"></a></h3><div><pre>6. Fields containing line breaks (CRLF), double quotes, and commas
   should be enclosed in double-quotes.  For example:

   "aaa","b CRLF
   bb","ccc" CRLF
   zzz,yyy,xxx</pre></div><p>Super CSV handles multi-line fields (as long as they're enclosed in quotes) when reading, and encloses a field in quotes when writing if it contains a newline, quote character or delimiter (defined in the <a href="./apidocs/org/supercsv/prefs/CsvPreference.html">CsvPreference</a> object).</p></div><div class="section"><h3>Rule 7<a name="Rule_7"></a></h3><div><pre>7. If double-quotes are used to enclose fields, then a double-quote
   appearing inside a field must be escaped by preceding it with
   another double quote.  For example:

   "aaa","b""bb","ccc"</pre></div><p>Super CSV escapes double-quotes with a preceding double-quote. Please note that the sometimes-used convention of escaping double-quotes as <tt>\"</tt> (instead of <tt>""</tt>) is <b>not supported</b>.</p></div></div>
</div>
<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
  <div class="container">
    <ul class="nav navbar-nav ">
       <li>{{ $copyright }}</li>
    </ul>
  </div>
</nav>

@stop
