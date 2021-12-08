<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	require_once(APPPATH . '/third_party/fpdf/fpdf.php');
	class Pdf extends FPDF
	{
		var $legends;
		var $wLegend;
		var $sum;
		var $NbVal;
		function __construct()
		{
			parent::__construct();
		}
		function RoundedRect($x, $y, $w, $h, $r, $style = '')
		{
			$k = $this->k;
			$hp = $this->h;
			if ($style=='F')
			{
				$op='f';
			}
			elseif($style=='FD' || $style=='DF')
			{
				$op='B';
			}
			else
			{
				$op='S';
			}
			$MyArc = 4/3 * (sqrt(2) - 1);
			$this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
			$xc = $x+$w-$r ;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
			$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
			$xc = $x+$w-$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
			$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
			$xc = $x+$r ;
			$yc = $y+$h-$r;
			$this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
			$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
			$xc = $x+$r ;
			$yc = $y+$r;
			$this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
			$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
			$this->_out($op);
		}
		function DashedRect($x1, $y1, $x2, $y2, $width=1, $nb=15)
		{
			$this->SetLineWidth($width);
			$longueur = abs($x1-$x2);
			$hauteur = abs($y1-$y2);
			if ($longueur>$hauteur)
			{
				$Pointilles=($longueur/$nb)/2; // length of dashes
			}
			else
			{
				$Pointilles=($hauteur/$nb)/2;
			}
			for ($i=$x1;$i<=$x2;$i+=$Pointilles+$Pointilles)
			{
				for ($j=$i;$j<=($i+$Pointilles);$j++)
				{
					if ($j<=($x2-1))
					{
						$this->Line($j,$y1,$j+1,$y1); // upper dashes
						$this->Line($j,$y2,$j+1,$y2); // lower dashes
					}
				}
			}
			for ($i=$y1;$i<=$y2;$i+=$Pointilles+$Pointilles)
			{
				for ($j=$i;$j<=($i+$Pointilles);$j++)
				{
					if ($j<=($y2-1))
					{
						$this->Line($x1,$j,$x1,$j+1); // left dashes
						$this->Line($x2,$j,$x2,$j+1); // right dashes
					}
				}
			}
		}
		function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
		{
			$h = $this->h;
			$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k, $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
		}
		function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
		{
			$d0 = $a - $b;
			if ($cw)
			{
				$d = $b;
				$b = $o - $a;
				$a = $o - $d;
			}
			else
			{
				$b += $o;
				$a += $o;
			}
			while($a<0)
			{
				$a += 360;
			}
			while($a>360)
			{
				$a -= 360;
			}
			while($b<0)
			{
				$b += 360;
			}
			while($b>360)
			{
				$b -= 360;
			}
			if ($a > $b)
			{
				$b += 360;
			}
			$b = $b/360*2*M_PI;
			$a = $a/360*2*M_PI;
			$d = $b - $a;
			if ($d == 0 && $d0 != 0)
			{
				$d = 2*M_PI;
			}
			$k = $this->k;
			$hp = $this->h;
			if (sin($d/2))
			{
				$MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
			}
			else
			{
				$MyArc = 0;
			}
			$this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
			$this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
			if ($d<M_PI/2)
			{
				$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
			}
			else
			{
				$b = $a + $d/4;
				$MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
				$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
			}
			$op = ($style=='F')?'f':(($style=='FD' || $style=='DF')?'b':'s');
			$this->_out($op);
		}
		function PieChart($w, $h, $data, $format, $colors=null)
		{
			$this->SetFont('Times', '', 10);
			$this->SetLegends($data,$format);
			$XPage = $this->GetX();
			$YPage = $this->GetY();
			$margin = 2;
			$hLegend = 5;
			$radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
			$radius = floor($radius / 2);
			$XDiag = $XPage + $margin + $radius;
			$YDiag = $YPage + $margin + $radius;
			if ($colors == null)
			{
				for ($i = 0; $i < $this->NbVal; $i++)
				{
					$gray = $i * intval(255 / $this->NbVal);
					$colors[$i] = array($gray,$gray,$gray);
				}
			}
			$this->SetLineWidth(0.2);
			$angleStart = 0;
			$angleEnd = 0;
			$i = 0;
			foreach ($data as $val)
			{
				$angle = ($val * 360) / doubleval($this->sum);
				if ($angle != 0)
				{
					$angleEnd = $angleStart + $angle;
					$this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
					$this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
					$angleStart += $angle;
				}
				$i++;
			}
			$this->SetFont('Times', '', 10);
			$x1 = $XPage + 2 * $radius + 4 * $margin;
			$x2 = $x1 + $hLegend + $margin;
			$y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
			for ($i=0; $i<$this->NbVal; $i++)
			{
				$this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
				$this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
				$this->SetXY($x2,$y1);
				$this->Cell(0,$hLegend,$this->legends[$i]);
				$y1+=$hLegend + $margin;
			}
		}
		function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
		{
			$this->SetFont('Courier', 'B', 10);
			$this->SetLegends($data,$format);
			$XPage = $this->GetX();
			$YPage = $this->GetY();
			$margin = 2;
			$YDiag = $YPage + $margin;
			$hDiag = floor($h - $margin * 2);
			$XDiag = $XPage + $margin * 2 + $this->wLegend;
			$lDiag = floor($w - $margin * 3 - $this->wLegend);
			if ($color == null)
			{
				$color=array(155,155,155);
			}
			if ($maxVal == 0)
			{
				$maxVal = max($data);
			}
			$valIndRepere = ceil($maxVal / $nbDiv);
			$maxVal = $valIndRepere * $nbDiv;
			$lRepere = floor($lDiag / $nbDiv);
			$lDiag = $lRepere * $nbDiv;
			$unit = $lDiag / $maxVal;
			$hBar = floor($hDiag / ($this->NbVal + 1));
			$hDiag = $hBar * ($this->NbVal + 1);
			$eBaton = floor($hBar * 80 / 100);
			$this->SetLineWidth(0.2);
			$this->Rect($XDiag, $YDiag, $lDiag, $hDiag);
			$this->SetFont('Courier', 'B', 10);
			$this->SetFillColor($color[0],$color[1],$color[2]);
			$i=0;
			foreach($data as $val)
			{
				$xval = $XDiag;
				$lval = (int)($val * $unit);
				$yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
				$hval = $eBaton;
				$this->Rect($xval, $yval, $lval, $hval, 'DF');
				$this->SetXY(0, $yval);
				$this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
				$i++;
			}
			for ($i = 0; $i <= $nbDiv; $i++)
			{
				$xpos = $XDiag + $lRepere * $i;
				$this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
				$val = $i * $valIndRepere;
				$xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
				$ypos = $YDiag + $hDiag - $margin;
				$this->Text($xpos, $ypos, $val);
			}
		}
		function SetLegends($data, $format)
		{
			$this->legends=array();
			$this->wLegend=0;
			$this->sum=array_sum($data);
			$this->NbVal=count($data);
			foreach($data as $l=>$val)
			{
				$p=sprintf('%.2f',$val/$this->sum*100).'%';
				$legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
				$this->legends[]=$legend;
				$this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
			}
		}
		function Header()
		{
		}
		function Footer()
		{
		}
	}