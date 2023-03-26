#include <avr/io.h>
#include <stdio.h>
#include <stdlib.h>
#define F_CPU 8000000L
#include <util/delay.h>

#define BAUDRATE 8
#define cap_sen_t1_low PE0

void usart_init(void);
void usart_tx(unsigned char);

int main (void)
{
	DDRE &= (1 << cap_sen_t1_low);
	usart_init();

	int cap_sen_t1_low_val = 0;

	while(1){
		cap_sen_t1_low_val = (PINE & (1 << cap_sen_t1_low)) >> cap_sen_t1_low;
		_delay_ms(100);
		usart_tx(cap_sen_t1_low_val);
	}
	return 0;
}

void usart_init (void){
	UBRRL=BAUDRATE;
	UCSRB|=(1<<TXEN)|(1<<RXEN);
	UCSRC|=(1<<URSEL)|(1<<UCSZ0)|(1<<UCSZ1);
}

void usart_tx (unsigned char data){
	while (!( UCSRA & (1<<UDRE)));
	UDR = data;
}


