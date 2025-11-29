// cart.js placeholder
function addToCart(itemId, qty){
    return fetch('/boltbite/cart/add', {method:'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({item: itemId, qty})});
}
