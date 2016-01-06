/**
 * Our custom, unique to site tests.
 */
customTests = function() {
  return [
    {
      id: "it should find the a visible add to cart link",
      result: function() {
        var element = document.querySelector('a.cart');
        return !!element && element.offsetParent !== null;
      }
    },
    {
      id: "it should find a visible title",
      result: function() {
        var element = document.querySelector('h1');
        return !!element && element.offsetParent !== null;
      }
    }
  ]
}
