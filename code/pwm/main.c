#define F_CPU 8000000UL
#include <avr/io.h>
#include <util/delay.h>

int main(void)
{
	// Set PD5 (OC1A) as output pin
	DDRD |= (1 << PD5);

	// Set Timer/Counter1 in Fast PWM mode, with non-inverted PWM output on OC1A
	TCCR1A |= (1 << COM1A1) | (1 << COM1A0) | (1 << WGM11) | (1 << WGM10);		//COM1A0 Inverts the signal
	TCCR1B |= /*(1 << WGM13) |*/ (1 << WGM12) /*| (1 << CS11)*/;

	// Set the prescaler value to divide the clock by 2 (producing a 1 MHz timer clock)
	TCCR1B |= (1 << CS10);

	// Set the output compare register (OCR1A) to produce a 70% duty cycle
	

	while (1)
	{
		OCR1A = 511;
		//_delay_ms(5000);
		//OCR1A = 0
		//_delay_ms(5000);
	}
	return 0;
}