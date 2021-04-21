import EmailsAPI from '~/api/emails';
import TokensAPI from '~/api/tokens';

export default function({ $axios }, inject) {
  const api = {
    emails: EmailsAPI( $axios ),
    tokens: TokensAPI( $axios ),
  }

  inject('api', api);
}