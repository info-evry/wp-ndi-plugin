Array.prototype.intersect = function ( array ) {
    const intersection = this.filter( item => array.includes( item ) );
    return [intersection, intersection.length, this, array];
};

async function fetchJSON ( url, retry = 5 ) {
    return await fetch( url ).then( async r => {
        if ( r.ok )
        {
            return await r.json()
                .then( r => {
                    return r;
                } )
                .catch( e => {
                    throw e;
                } );
        } else
        {
            return retry > 0 ? await fetchJSON( url, retry - 1 ) : r;
        }
    } );
}