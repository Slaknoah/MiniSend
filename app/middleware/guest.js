export default function( context ) {
  if ( context.$auth.loggedIn ) {
    return context.error({
      statusCode: 403,
      message: 'Access denied!',
    });
  }
}