/* global jsPDF */

function demoTwoPageDocument() {
  var doc = new jsPDF();
  doc.text(20, 20, "Hello world!");
  doc.text(20, 30, "This is client-side Javascript, pumping out a PDF.");
  doc.addPage();
  doc.text(20, 20, "Do you like that?");

  // Save the PDF
  doc.save("Test.pdf");
}

function demoFontSizes() {
  var doc = new jsPDF();
  doc.setFontSize(22);
  doc.text(20, 20, "This is a title");

  doc.setFontSize(16);
  doc.text(20, 30, "This is some normal sized text underneath.");

  doc.save("Test.pdf");
}

function demoFontTypes() {
  var doc = new jsPDF();

  doc.text(20, 20, "This is the default font.");

  doc.setFont("courier");
  doc.setFontType("normal");
  doc.text(20, 30, "This is courier normal.");

  doc.setFont("times");
  doc.setFontType("italic");
  doc.text(20, 40, "This is times italic.");

  doc.setFont("helvetica");
  doc.setFontType("bold");
  doc.text(20, 50, "This is helvetica bold.");

  doc.setFont("courier");
  doc.setFontType("bolditalic");
  doc.text(20, 60, "This is courier bolditalic.");

  doc.save("Test.pdf");
}