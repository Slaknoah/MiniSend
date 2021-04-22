import EmailsAPI from '~/api/emails';
import TokensAPI from '~/api/tokens';
import RecipientsAPI from '~/api/recipients';

export default function({ $axios }, inject) {
  const api = {
    emails: EmailsAPI( $axios ),
    tokens: TokensAPI( $axios ),
    recipients: RecipientsAPI($axios)
  }

  inject('api', api);
}