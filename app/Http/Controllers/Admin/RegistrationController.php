<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegistrationController extends Controller
{
    private function excelEscape(mixed $value): string
    {
        $s = (string) ($value ?? '');
        $s = preg_replace("/\\R/u", ' ', $s) ?? $s;

        // Prevent Excel formula injection
        if ($s !== '' && preg_match('/^[=+\\-@]/', $s)) {
            $s = "'".$s;
        }

        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function index(Request $request)
    {
        $regs = Registration::query()
            ->with('eventSession.venue')
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.registrations.index', [
            'registrations' => $regs,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $query = Registration::query()
            ->with('eventSession.venue')
            ->orderBy('created_at');
        $filename = 'registrations.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            $delimiter = ',';
            $eol = "\r\n";

            // Excel reliably reads UTF-16LE CSV with BOM (fixes Vietnamese mojibake)
            fwrite($out, "\xFF\xFE");

            $writeLine = function (string $line) use ($out) {
                $encoded = iconv('UTF-8', 'UTF-16LE//IGNORE', $line);
                if ($encoded !== false) {
                    fwrite($out, $encoded);
                }
            };

            $writeRow = function (array $row) use ($delimiter, $eol, $writeLine) {
                $tmp = fopen('php://temp', 'r+');
                fputcsv($tmp, $row, $delimiter, '"', '\\', $eol);
                rewind($tmp);
                $line = stream_get_contents($tmp);
                fclose($tmp);
                $writeLine($line !== false ? $line : '');
            };

            // Hint Excel which delimiter to use
            $writeLine("sep=,{$eol}");

            $writeRow(
                [
                    'Mã',
                    'Tạo lúc',
                    'Địa điểm',
                    'Suất diễn',
                    'Họ tên',
                    'Email',
                    'Phone',
                    'Khách',
                    'NTL',
                    'NTL mới',
                    'Trẻ em',
                    'Tổng',
                    'Đi cùng khách',
                    'Trạng thái',
                ]
            );

            $query->chunk(500, function ($rows) use ($writeRow) {
                foreach ($rows as $r) {
                    $writeRow(
                        [
                            $r->id,
                            $r->created_at?->format('d/m/Y H:i'),
                            $r->eventSession?->venue?->name,
                            $r->eventSession?->starts_at?->format('d/m/Y H:i'),
                            $r->full_name,
                            $r->email,
                            $r->phone,
                            $r->adult_count,
                            $r->ntl_count,
                            $r->ntl_new_count,
                            $r->child_count,
                            $r->total_count,
                            $r->attend_with_guest ? 'Có' : 'Không',
                            $r->status,
                        ]
                    );
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-16LE',
        ]);
    }

    public function exportXls(Request $request): StreamedResponse
    {
        $query = Registration::query()
            ->with('eventSession.venue')
            ->orderBy('created_at');

        $filename = 'registrations.xls';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');

            fwrite($out, "<!doctype html>\n");
            fwrite($out, "<html><head><meta charset=\"UTF-8\">\n");
            fwrite($out, "<style>
                td,th{border:1px solid #ddd;padding:7px 10px;font-family:Arial;font-size:12px;vertical-align:top}
                th{background:#f3f4f6;font-weight:700}
                table{border-collapse:collapse;table-layout:fixed}
            </style>\n");
            fwrite($out, "</head><body>\n");
            fwrite($out, "<table>\n");
            // Column widths (Excel tends to respect colgroup widths for HTML .xls)
            fwrite($out, "<colgroup>");
            fwrite($out, "<col style=\"width:60px\">");   // Mã
            fwrite($out, "<col style=\"width:120px\">");  // Tạo lúc
            fwrite($out, "<col style=\"width:160px\">");  // Địa điểm
            fwrite($out, "<col style=\"width:140px\">");  // Suất diễn
            fwrite($out, "<col style=\"width:180px\">");  // Họ tên
            fwrite($out, "<col style=\"width:240px\">");  // Email
            fwrite($out, "<col style=\"width:140px\">");  // Phone
            fwrite($out, "<col style=\"width:80px\">");   // Khách
            fwrite($out, "<col style=\"width:80px\">");   // NTL
            fwrite($out, "<col style=\"width:80px\">");   // NTL mới
            fwrite($out, "<col style=\"width:80px\">");   // Trẻ em
            fwrite($out, "<col style=\"width:80px\">");   // Tổng
            fwrite($out, "<col style=\"width:120px\">");  // Đi cùng khách
            fwrite($out, "<col style=\"width:100px\">");  // Trạng thái
            fwrite($out, "</colgroup>\n");
            fwrite($out, "<thead><tr>");

            $headers = [
                'Mã',
                'Tạo lúc',
                'Địa điểm',
                'Suất diễn',
                'Họ tên',
                'Email',
                'Phone',
                'Khách',
                'NTL',
                'NTL mới',
                'Trẻ em',
                'Tổng',
                'Đi cùng khách',
                'Trạng thái',
            ];

            foreach ($headers as $h) {
                fwrite($out, '<th>'.$this->excelEscape($h).'</th>');
            }
            fwrite($out, "</tr></thead>\n<tbody>\n");

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fwrite($out, "<tr>");
                    $cells = [
                        $r->id,
                        $r->created_at?->format('d/m/Y H:i'),
                        $r->eventSession?->venue?->name,
                        $r->eventSession?->starts_at?->format('d/m/Y H:i'),
                        $r->full_name,
                        $r->email,
                        $r->phone,
                        $r->adult_count,
                        $r->ntl_count,
                        $r->ntl_new_count,
                        $r->child_count,
                        $r->total_count,
                        $r->attend_with_guest ? 'Có' : 'Không',
                        $r->status,
                    ];

                    foreach ($cells as $c) {
                        fwrite($out, '<td>'.$this->excelEscape($c).'</td>');
                    }
                    fwrite($out, "</tr>\n");
                }
            });

            fwrite($out, "</tbody></table>\n</body></html>");
            fclose($out);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }
}
