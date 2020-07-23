type MessageType = 'error' | 'success' | 'warn' | null;
type TransactionType = 'credit' | 'debit';
interface AlertMessage {
    type: MessageType;
    text: string;
    errors?: Array<string>;
}
