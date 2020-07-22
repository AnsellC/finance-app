type MessageType = 'error' | 'success' | 'warn' | null;
interface AlertMessage {
    type: MessageType;
    text: string;
}
